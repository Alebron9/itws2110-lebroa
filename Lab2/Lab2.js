document.addEventListener("DOMContentLoaded", () => {
    const articles = document.querySelectorAll(".constitution-body");

    articles.forEach(article => {
        // Get all children except the title
        const children = Array.from(article.children).filter(
            child => !child.classList.contains("article-title")
        );

        // Wrap the rest of the content in a div
        const contentWrapper = document.createElement("div");
        contentWrapper.classList.add("hidden-content");

        children.forEach(child => contentWrapper.appendChild(child));
        article.appendChild(contentWrapper);

        // Add hover behavior
        article.addEventListener("mouseenter", () => {
            contentWrapper.style.maxHeight = contentWrapper.scrollHeight + "px";
            contentWrapper.style.opacity = "1";
        });

        article.addEventListener("mouseleave", () => {
            contentWrapper.style.maxHeight = "0";
            contentWrapper.style.opacity = "0";
        });
    });
});