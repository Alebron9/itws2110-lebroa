# Puerto Rican Restaurant Menu

## Key Changes Made

### Color Adjustments
- Changed prices from pink/red to **black** for better readability
- Changed category badge colors many times: **red** (non-vegetarian), **dark green** (vegetarian), **light green** (vegan)
- Changed main background from gradient to **solid brown**
- Updated table header from many color gradient to end up in **light brown** (I was trying to se what colors were good for the menu and whent from purple to brown)

### Removed Animations
- Removed **color transitions** on header, footer, and background (didnt like how it was so made it a simple color)
- Kept only subtle hover effects on dropdown and table rows (originaly had way to many)

### Technical Implementation
- **AJAX with XMLHttpRequest** to fetch data from `data/menu.json`
- **Category filtering** via dropdown menu
- **Error handling** with placeholder images for missing files
- **Semantic HTML** table structure

### Code Style
- 2-space indentation
- camelCase for JavaScript
- Lowercase-hyphen for HTML IDs