#!/usr/bin/env python3
"""
Generate Invitatorio favicon set from scratch.

Brand: Invitatorio (Invitaciones Digitales)
Colors: Orange #EB7512 -> Purple #5A3087 (diagonal gradient)
Design: Rounded square with brand gradient + white envelope + "i" badge in corner.

Outputs (in public/ and public/images/):
- public/favicon.ico              (multi-resolution: 16, 32, 48)
- public/images/favicon.svg       (vector source)
- public/images/favicon-32.png    (32x32)
- public/images/favicon-16.png    (16x16)
- public/images/apple-touch-icon.png   (180x180)
- public/images/android-chrome-192.png (192x192)
- public/images/android-chrome-512.png (512x512)
"""

from PIL import Image, ImageDraw
import os
import math
import struct

# --- Brand colors ---
ORANGE = (235, 117, 18, 255)    # #EB7512
PURPLE = (90, 48, 135, 255)     # #5A3087
WHITE  = (255, 253, 248, 255)   # #FFFDF8 (warm off-white from brand)


# --- Drawing primitives -------------------------------------------------

def lerp(a, b, t):
    return tuple(int(round(a[i] + (b[i] - a[i]) * t)) for i in range(len(a)))


def fill_gradient(img, box, c1, c2, angle=45):
    """Diagonal linear gradient fill (angle in degrees, 0 = horizontal L->R)."""
    x0, y0, x1, y1 = box
    w, h = x1 - x0, y1 - y0
    rad = math.radians(angle)
    dx, dy = math.cos(rad), math.sin(rad)
    # Project each pixel onto the gradient direction, find min/max for normalization.
    corners = [(0, 0), (w, 0), (0, h), (w, h)]
    projs = [px * dx + py * dy for px, py in corners]
    pmin, pmax = min(projs), max(projs)
    if pmax == pmin:
        # single color fallback
        ImageDraw.Draw(img).rectangle(box, fill=c1[:3] + (255,) if len(c1) == 4 else c1)
        return
    px_draw = ImageDraw.Draw(img)
    for y in range(h):
        for x in range(w):
            t = (x * dx + y * dy - pmin) / (pmax - pmin)
            t = max(0.0, min(1.0, t))
            color = lerp(c1, c2, t)
            px_draw.point((x0 + x, y0 + y), fill=color)


def rounded_rect(draw, box, radius, fill=None, outline=None, width=1):
    draw.rounded_rectangle(box, radius=radius, fill=fill, outline=outline, width=width)


def draw_envelope(img, size, scale=0.62, cx=None, cy=None):
    """Draws a centered white envelope onto img (RGBA)."""
    w, h = img.size
    if cx is None:
        cx = w / 2
    if cy is None:
        cy = h / 2
    s = size
    env_w = s * scale
    env_h = s * scale * 0.66
    # Envelope body
    x0 = cx - env_w / 2
    y0 = cy - env_h / 2 + s * 0.04  # nudge down slightly to leave room for flap above
    x1 = x0 + env_w
    y1 = y0 + env_h
    r = s * 0.04  # corner radius
    overlay = Image.new("RGBA", img.size, (0, 0, 0, 0))
    od = ImageDraw.Draw(overlay)
    # Body
    od.rounded_rectangle((x0, y0, x1, y1), radius=r, fill=WHITE)
    # The V-flap drawn as a polygon (the open envelope flap)
    flap_h = env_h * 0.55
    flap = [
        (x0, y0),
        (cx, y0 + flap_h),
        (x1, y0),
    ]
    od.polygon(flap, fill=WHITE, outline=WHITE)
    # Draw the V-line in the same white-on-white but add a subtle separator at the fold
    # by drawing the inner triangle slightly darker using brand orange at low opacity.
    sep = (235, 117, 18, 90)
    od.line([(x0, y0), (cx, y0 + flap_h), (x1, y0)], fill=sep, width=max(1, int(s * 0.012)))
    img.alpha_composite(overlay)


def draw_i_badge(img, size):
    """Draws the corner 'i' notification badge (small white circle with orange 'i')."""
    w, h = img.size
    overlay = Image.new("RGBA", img.size, (0, 0, 0, 0))
    od = ImageDraw.Draw(overlay)
    badge_r = size * 0.20
    bx = w - badge_r * 0.95
    by = badge_r * 0.95
    # White circle
    od.ellipse((bx - badge_r, by - badge_r, bx + badge_r, by + badge_r), fill=WHITE)
    # Subtle ring
    od.ellipse((bx - badge_r, by - badge_r, bx + badge_r, by + badge_r),
               outline=(0, 0, 0, 30), width=1)
    # Orange 'i' (dot + stem)
    dot_r = badge_r * 0.18
    od.ellipse((bx - dot_r, by - badge_r * 0.45 - dot_r,
                bx + dot_r, by - badge_r * 0.45 + dot_r), fill=ORANGE)
    stem_w = badge_r * 0.30
    stem_h = badge_r * 0.55
    stem_y_top = by - badge_r * 0.05
    stem_y_bot = stem_y_top + stem_h
    od.rounded_rectangle((bx - stem_w / 2, stem_y_top,
                          bx + stem_w / 2, stem_y_bot),
                         radius=stem_w / 2, fill=ORANGE)
    img.alpha_composite(overlay)


def draw_favicon_canvas(size):
    """Render the full favicon at a given size and return an RGBA Image."""
    img = Image.new("RGBA", (size, size), (0, 0, 0, 0))
    radius = size * 0.22  # rounded square radius (iOS-style)
    # 1) Rounded square base with diagonal orange->purple gradient
    base = Image.new("RGBA", (size, size), (0, 0, 0, 0))
    fill_gradient(base, (0, 0, size, size), ORANGE, PURPLE, angle=45)
    # Mask base with rounded rect
    mask = Image.new("L", (size, size), 0)
    md = ImageDraw.Draw(mask)
    md.rounded_rectangle((0, 0, size - 1, size - 1), radius=radius, fill=255)
    img.paste(base, (0, 0), mask)

    # 2) Envelope
    draw_envelope(img, size)

    # 3) "i" badge (skip on very small sizes to keep the icon legible)
    if size >= 32:
        draw_i_badge(img, size)
    return img


# --- ICO writer ---------------------------------------------------------

def write_ico(images, path):
    """Write a multi-resolution .ico file. images: list of (size, Image.RGBA)."""
    n = len(images)
    # ICONDIR header (6 bytes) + ICONDIRENTRY * n (16 bytes each)
    header = struct.pack("<HHH", 0, 1, n)
    offset = 6 + 16 * n
    entries = b""
    payloads = b""
    for size, img in images:
        # ICO stores PNG payloads directly for >=Vista; we use PNG for clarity.
        from io import BytesIO
        buf = BytesIO()
        img.save(buf, format="PNG", optimize=True)
        data = buf.getvalue()
        w = size if size < 256 else 0  # 0 means 256
        h = size if size < 256 else 0
        entries += struct.pack("<BBBBHHII", w, h, 0, 0, 1, 32, len(data), offset)
        payloads += data
        offset += len(data)
    with open(path, "wb") as f:
        f.write(header + entries + payloads)


# --- SVG writer (vector source for the brand) ---------------------------

SVG_TEMPLATE = '''<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" width="64" height="64">
  <defs>
    <linearGradient id="g" x1="0" y1="0" x2="1" y2="1">
      <stop offset="0" stop-color="#EB7512"/>
      <stop offset="1" stop-color="#5A3087"/>
    </linearGradient>
  </defs>
  <rect x="2" y="2" width="60" height="60" rx="14" fill="url(#g)"/>
  <g>
    <rect x="14" y="22" width="36" height="22" rx="3" fill="#FFFDF8"/>
    <path d="M14 24 L32 36 L50 24" stroke="#EB7512" stroke-width="2" fill="none" stroke-linejoin="round" stroke-linecap="round"/>
  </g>
  <circle cx="49" cy="17" r="7" fill="#FFFDF8"/>
  <circle cx="49" cy="14.5" r="1.6" fill="#EB7512"/>
  <rect x="47.6" y="17" width="2.8" height="5" rx="1.4" fill="#EB7512"/>
</svg>
'''


# --- Main ---------------------------------------------------------------

def main():
    repo_root = os.path.abspath(os.path.join(os.path.dirname(__file__), ".."))
    public_dir = os.path.join(repo_root, "public")
    images_dir = os.path.join(public_dir, "images")
    os.makedirs(images_dir, exist_ok=True)

    print("Generating favicon canvas at master size 512...")
    master = draw_favicon_canvas(512)
    sizes = [16, 32, 48, 64, 128, 180, 192, 256, 512]

    rendered = {}
    for s in sizes:
        if s == 512:
            rendered[s] = master
        else:
            # High-quality downscale: Lanczos for smoothness
            rendered[s] = master.resize((s, s), Image.LANCZOS)

    # Write individual PNGs
    targets = {
        16:  os.path.join(images_dir, "favicon-16.png"),
        32:  os.path.join(images_dir, "favicon-32.png"),
        180: os.path.join(images_dir, "apple-touch-icon.png"),
        192: os.path.join(images_dir, "android-chrome-192.png"),
        512: os.path.join(images_dir, "android-chrome-512.png"),
    }
    for s, path in targets.items():
        rendered[s].save(path, format="PNG", optimize=True)
        print(f"  -> {os.path.relpath(path, repo_root)}  ({s}x{s})")

    # Write multi-resolution .ico (16, 32, 48) for browser tab
    ico_path = os.path.join(public_dir, "favicon.ico")
    write_ico([(16, rendered[16]), (32, rendered[32]), (48, rendered[48])], ico_path)
    print(f"  -> {os.path.relpath(ico_path, repo_root)}  (16/32/48 multi-res)")

    # Write the SVG source for the layout's <link rel="icon" type="image/svg+xml">
    svg_path = os.path.join(images_dir, "favicon.svg")
    with open(svg_path, "w", encoding="utf-8") as f:
        f.write(SVG_TEMPLATE)
    print(f"  -> {os.path.relpath(svg_path, repo_root)}  (vector)")

    print("\nDone.")


if __name__ == "__main__":
    main()
