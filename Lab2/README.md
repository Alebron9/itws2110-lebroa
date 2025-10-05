## Overview
In this lab, I enhanced the **U.S. Constitution webpage** by adding interactivity with JavaScript.
The main goal was to take each block of the Constitution or Amendment (`.constitution-body`) and make it **collapsed by default**, showing only the **main title**. When a user hovers over the block, the rest of the content expands smoothly. (This was implemented in a **separate JavaScript file**)

---------------------------------------------------------------------------------------------------------------

## How It Works
- Each `.constitution-body` is scanned when the page loads.
- Everything except the `.article-title` inside the block is moved into a wrapper `<div>` with the class `.hidden-content`.
- CSS ensures that `.hidden-content` is hidden by default (`max-height: 0; opacity: 0;`).
- When the user **hovers** over the `.constitution-body`, JavaScript expands the `.hidden-content` with a transition so the full text is revealed.
- When the user **moves the mouse away**, the content collapses again.

----------------------------------------------------------------------------------------------------------------

## Biggest Challenges
1. **Separating content without breaking formatting**
   - The Constitution page has lots of nested elements (titles, sections, paragraphs, annotations).
   - The challenge was to wrap everything except the main title (`.article-title`) into a container that could be toggled, while keeping the original HTML structure intact.

2. **CSS transitions for expanding/collapsing**
   - Simply hiding content with `display: none` didn’t allow smooth animations.
   - I had to use `max-height` and `opacity` with transitions to make the hover effect look smooth and natural.

3. **Keeping the script modular**  
   - At first, I placed the script inline in the HTML. Later, I moved it into a separate `constitution.js` file for better project organization.
   - Linking the JS at the bottom of the HTML body ensured the DOM was loaded before the script ran.