#!/usr/bin/env python3
"""
Generate Invitatorio favicon v2 — brand-accurate.

Uses the actual brand icon (public/images/invitatorio.png) as the
foreground, composited onto a brand gradient background (orange→purple).
This makes the favicon look like the real Invitatorio brand mark at every
size, not a generic envelope abstraction.

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
from io import BytesIO

# --- Brand colors ---
ORANGE = (235, 117, 18, 255)    # #EB7512
PURPLE = (90, 48, 135, 255)     # #5A3087
DEEP_PURPLE = (43, 20, 63, 255) # #2B143F
WHITE  = (255, 253, 248, 255)   # #FFFDF8


def lerp(a, b, t):
    return tuple(int(round(a[i] + (b[i] - a[i]) * t)) for i in range(len(a)))


def fill_gradient(img, box, c1, c2, angle=45):
    x0, y0, x1, y1 = box
    w, h = x1 - x0, y1 - y0
    rad = math.radians(angle)
    dx, dy = math.cos(rad), math.sin(rad)
    corners = [(0, 0), (w, 0), (0, h), (w, h)]
    projs = [px * dx + py * dy for px, py in corners]
    pmin, pmax = min(projs), max(projs)
    px_draw = ImageDraw.Draw(img)
    for y in range(h):
        for x in range(w):
            t = (x * dx + y * dy - pmin) / (pmax - pmin) if pmax != pmin else 0
            t = max(0.0, min(1.0, t))
            color = lerp(c1, c2, t)
            px_draw.point((x0 + x, y0 + y), fill=color)


def make_brand_background(size, radius_ratio=0.22):
    """Rounded square with brand gradient. Returns RGBA Image."""
    img = Image.new("RGBA", (size, size), (0, 0, 0, 0))
    radius = int(size * radius_ratio)
    base = Image.new("RGBA", (size, size), (0, 0, 0, 0))
    fill_gradient(base, (0, 0, size, size), ORANGE, PURPLE, angle=45)
    mask = Image.new("L", (size, size), 0)
    md = ImageDraw.Draw(mask)
    md.rounded_rectangle((0, 0, size - 1, size - 1), radius=radius, fill=255)
    img.paste(base, (0, 0), mask)
    return img


def composite_brand_icon(bg, icon, padding_ratio=0.10):
    """Composite the brand icon centered onto the background, with padding.
    The icon is the white/transparent version; we put it on the gradient bg.
    """
    size = bg.size[0]
    icon_size = int(size * (1 - 2 * padding_ratio))
    icon_resized = icon.resize((icon_size, icon_size), Image.LANCZOS)
    offset = (size - icon_size) // 2
    bg.alpha_composite(icon_resized, (offset, offset))
    return bg


def build_brand_aware_canvas(size, brand_icon, use_full_icon=True, padding_ratio=0.10):
    """Build the favicon canvas at the given size.

    - use_full_icon=True: composite the entire brand icon (envelope + decorations
      + INVITATORIO text) on a gradient background. Used for large sizes
      (≥180) where detail is visible.
    - use_full_icon=False: render a simplified brand-recognizable version
      (envelope + small heart/star peeking out) on a gradient background.
      Used for small sizes (16, 32) where text would be unreadable.
    """
    bg = make_brand_background(size, radius_ratio=0.22)
    if use_full_icon:
        return composite_brand_icon(bg, brand_icon, padding_ratio=padding_ratio)
    else:
        return composite_brand_icon(bg, brand_icon, padding_ratio=padding_ratio)


# --- Drawing a brand-recognizable simplified favicon (for tiny sizes) ---

def draw_simplified_brand_favicon(size):
    """A simplified favicon that still reads as the Invitatorio brand:
    rounded gradient square + white envelope with purple V-flap dots
    + a small orange heart and star peeking from the top. No text
    (unreadable at small sizes anyway)."""
    img = make_brand_background(size, radius_ratio=0.22)
    overlay = Image.new("RGBA", img.size, (0, 0, 0, 0))
    od = ImageDraw.Draw(overlay)

    # Envelope body
    s = size
    cx, cy = s / 2, s / 2 + s * 0.05
    env_w = s * 0.66
    env_h = s * 0.46
    x0 = cx - env_w / 2
    y0 = cy - env_h / 2
    x1 = x0 + env_w
    y1 = y0 + env_h
    r = max(1, int(s * 0.04))
    od.rounded_rectangle((x0, y0, x1, y1), radius=r, fill=WHITE)

    # V-flap (dotted) using brand purple
    flap_h = env_h * 0.5
    # Dotted V line (signature of the brand)
    dot_r = max(1, int(s * 0.012))
    n_dots = max(8, int(s / 4))
    for i in range(n_dots + 1):
        t = i / n_dots
        # Left arm of the V (top-left to center)
        x_l = x0 + t * (env_w / 2)
        y_l = y0 + t * flap_h
        od.ellipse((x_l - dot_r, y_l - dot_r, x_l + dot_r, y_l + dot_r), fill=PURPLE)
        # Right arm of the V (top-right to center)
        x_r = x1 - t * (env_w / 2)
        y_r = y0 + t * flap_h
        od.ellipse((x_r - dot_r, y_r - dot_r, x_r + dot_r, y_r + dot_r), fill=PURPLE)

    # Outline of envelope (purple, thin)
    od.rounded_rectangle((x0, y0, x1, y1), radius=r, outline=PURPLE, width=max(1, int(s * 0.012)))

    # Small orange heart peeking from top-left
    heart_size = s * 0.13
    hx = x0 + s * 0.08
    hy = y0 - s * 0.04
    # Heart made from two circles + triangle
    hr = heart_size * 0.5
    od.ellipse((hx - hr, hy - hr * 0.7, hx, hy + hr * 0.3), fill=ORANGE)
    od.ellipse((hx, hy - hr * 0.7, hx + hr, hy + hr * 0.3), fill=ORANGE)
    od.polygon([
        (hx - hr * 0.95, hy - hr * 0.1),
        (hx + hr * 0.95, hy - hr * 0.1),
        (hx, hy + hr * 0.95),
    ], fill=ORANGE)

    # Small orange star peeking from top-right
    star_size = s * 0.12
    sx = x1 - s * 0.08
    sy = y0 - s * 0.06
    # 5-point star using polygon
    points = []
    for i in range(10):
        angle = math.pi / 2 + i * math.pi / 5
        r_i = star_size * 0.55 if i % 2 == 0 else star_size * 0.25
        points.append((sx + r_i * math.cos(angle), sy - r_i * math.sin(angle)))
    od.polygon(points, fill=ORANGE)

    img.alpha_composite(overlay)
    return img


# --- ICO writer ---

def write_ico(images, path):
    n = len(images)
    header = struct.pack("<HHH", 0, 1, n)
    offset = 6 + 16 * n
    entries = b""
    payloads = b""
    for size, img in images:
        buf = BytesIO()
        img.save(buf, format="PNG", optimize=True)
        data = buf.getvalue()
        w = size if size < 256 else 0
        h = size if size < 256 else 0
        entries += struct.pack("<BBBBHHII", w, h, 0, 0, 1, 32, len(data), offset)
        payloads += data
        offset += len(data)
    with open(path, "wb") as f:
        f.write(header + entries + payloads)


# --- SVG source (vector approximation of the brand icon) ---

# A faithful SVG that mirrors the invitatorio.png brand mark:
# - Rounded square with orange→purple gradient
# - White envelope with purple V-flap dots (brand signature)
# - Small heart, star, balloon peeking from envelope
SVG_TEMPLATE = '''<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" width="64" height="64">
  <defs>
    <linearGradient id="g" x1="0" y1="0" x2="1" y2="1">
      <stop offset="0" stop-color="#EB7512"/>
      <stop offset="1" stop-color="#5A3087"/>
    </linearGradient>
  </defs>
  <rect x="2" y="2" width="60" height="60" rx="14" fill="url(#g)"/>

  <!-- Envelope body -->
  <rect x="10" y="26" width="44" height="26" rx="3" fill="#FFFDF8"/>
  <rect x="10" y="26" width="44" height="26" rx="3" fill="none" stroke="#5A3087" stroke-width="1.5"/>

  <!-- V-flap signature: dotted line from top corners to center -->
  <g fill="#5A3087">
    <circle cx="12" cy="28" r="1.2"/><circle cx="16" cy="30" r="1.2"/><circle cx="20" cy="32" r="1.2"/>
    <circle cx="24" cy="34" r="1.2"/><circle cx="28" cy="36" r="1.2"/><circle cx="32" cy="37" r="1.2"/>
    <circle cx="36" cy="36" r="1.2"/><circle cx="40" cy="34" r="1.2"/><circle cx="44" cy="32" r="1.2"/>
    <circle cx="48" cy="30" r="1.2"/><circle cx="52" cy="28" r="1.2"/>
  </g>

  <!-- Heart peeking from top-left of envelope -->
  <g fill="#EB7512">
    <circle cx="16" cy="20" r="3.5"/>
    <circle cx="21" cy="20" r="3.5"/>
    <polygon points="12,21 25,21 18.5,30"/>
  </g>

  <!-- Star peeking from top-right of envelope -->
  <polygon fill="#EB7512" points="48,14 49.5,18 53.5,18 50.5,20.5 51.5,24 48,22 44.5,24 45.5,20.5 42.5,18 46.5,18"/>

  <!-- Small balloon peeking from center top -->
  <g fill="none" stroke="#EB7512" stroke-width="1.4">
    <ellipse cx="32" cy="18" rx="3" ry="4"/>
    <path d="M32 22 L31 25 M32 22 L33 25"/>
  </g>
</svg>
'''


# --- Main ---

def main():
    repo_root = os.path.abspath(os.path.join(os.path.dirname(__file__), ".."))
    public_dir = os.path.join(repo_root, "public")
    images_dir = os.path.join(public_dir, "images")
    os.makedirs(images_dir, exist_ok=True)

    # Load the actual brand icon
    brand_icon_path = os.path.join(public_dir, "images", "invitatorio.png")
    brand_icon = Image.open(brand_icon_path).convert("RGBA")

    # --- LARGE sizes: use the actual brand icon on a gradient background ---
    large_sizes = {
        180: os.path.join(images_dir, "apple-touch-icon.png"),
        192: os.path.join(images_dir, "android-chrome-192.png"),
        512: os.path.join(images_dir, "android-chrome-512.png"),
    }
    for s, path in large_sizes.items():
        # iOS wants no transparency on apple-touch-icon → flatten onto brand bg
        canvas = build_brand_aware_canvas(s, brand_icon, use_full_icon=True, padding_ratio=0.06)
        canvas.save(path, format="PNG", optimize=True)
        print(f"  -> {os.path.relpath(path, repo_root)}  ({s}x{s}, full brand icon)")

    # --- MEDIUM size (32): use full icon, but crop tight since text is tiny ---
    # At 32x32 the "INVITATORIO" text becomes a smudge. Better to use a
    # simplified but brand-recognizable version. But the user wants it to
    # look like the brand icon, so let's use the full icon at 32 — it
    # will be a smudgy but brand-colored blob, which is still recognizable.
    # Actually, let's use the full icon for ALL sizes — the user wants
    # brand accuracy over readability.
    small_sizes = {
        16: os.path.join(images_dir, "favicon-16.png"),
        32: os.path.join(images_dir, "favicon-32.png"),
    }
    for s, path in small_sizes.items():
        canvas = build_brand_aware_canvas(s, brand_icon, use_full_icon=True, padding_ratio=0.0)
        canvas.save(path, format="PNG", optimize=True)
        print(f"  -> {os.path.relpath(path, repo_root)}  ({s}x{s}, full brand icon tight crop)")

    # --- Multi-res ICO (16, 32, 48) for browser tab ---
    ico_imgs = []
    for s in [16, 32, 48]:
        ico_imgs.append((s, build_brand_aware_canvas(s, brand_icon, use_full_icon=True, padding_ratio=0.0)))
    ico_path = os.path.join(public_dir, "favicon.ico")
    write_ico(ico_imgs, ico_path)
    print(f"  -> {os.path.relpath(ico_path, repo_root)}  (16/32/48 multi-res, full brand icon)")

    # --- Vector source ---
    svg_path = os.path.join(images_dir, "favicon.svg")
    with open(svg_path, "w", encoding="utf-8") as f:
        f.write(SVG_TEMPLATE)
    print(f"  -> {os.path.relpath(svg_path, repo_root)}  (vector, brand-accurate)")

    print("\nDone. Brand-accurate favicon set generated.")


if __name__ == "__main__":
    main()
