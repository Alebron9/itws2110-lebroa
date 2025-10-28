const menuBody = document.getElementById("menu-body");
const menuTable = document.getElementById("menu-table");
const loadingDiv = document.getElementById("loading");
const categoryFilter = document.getElementById("category-filter");

let allDishes = [];

// === Fetch menu data using AJAX ===
function loadMenu() {
  const xhr = new XMLHttpRequest();
  
  xhr.onreadystatechange = function() {
    if (xhr.readyState === 4) {
      if (xhr.status === 200) {
        try {
          const data = JSON.parse(xhr.responseText);
          allDishes = data.dishes;
          displayMenu(allDishes);
          loadingDiv.style.display = "none";
          menuTable.style.display = "table";
        } catch (error) {
          loadingDiv.innerHTML = "Error parsing menu data.";
          console.error(error);
        }
      } else {
        loadingDiv.innerHTML = "Unable to load menu. Please try again later.";
        console.error("Failed to load menu.json");
      }
    }
  };
  
  xhr.open("GET", "data/menu.json", true);
  xhr.send();
}

// === Display menu items in table ===
function displayMenu(dishes) {
  menuBody.innerHTML = "";
  
  dishes.forEach(dish => {
    const row = document.createElement("tr");
    row.setAttribute("data-category", dish.category);
    
    // Image cell
    const imageCell = document.createElement("td");
    const img = document.createElement("img");
    img.src = dish.image;
    img.alt = dish.name;
    img.className = "dish-image";
    img.onerror = function() {
      this.src = "https://via.placeholder.com/120x120?text=No+Image";
    };
    imageCell.appendChild(img);
    
    // Dish name and cuisine cell
    const nameCell = document.createElement("td");
    const nameDiv = document.createElement("div");
    nameDiv.className = "dish-name";
    nameDiv.textContent = dish.name;
    const cuisineDiv = document.createElement("div");
    cuisineDiv.className = "dish-cuisine";
    cuisineDiv.textContent = dish.cuisine;
    nameCell.appendChild(nameDiv);
    nameCell.appendChild(cuisineDiv);
    
    // Description cell
    const descCell = document.createElement("td");
    const descDiv = document.createElement("div");
    descDiv.className = "dish-description";
    descDiv.textContent = dish.description;
    descCell.appendChild(descDiv);
    
    // Category cell
    const categoryCell = document.createElement("td");
    const categoryBadge = document.createElement("span");
    categoryBadge.className = `category-badge category-${dish.category}`;
    categoryBadge.textContent = dish.category.replace("-", " ");
    categoryCell.appendChild(categoryBadge);
    
    // Ingredients cell
    const ingredientsCell = document.createElement("td");
    const ingredientsList = document.createElement("ul");
    ingredientsList.className = "ingredients-list";
    dish.ingredients.forEach(ingredient => {
      const li = document.createElement("li");
      li.textContent = ingredient;
      ingredientsList.appendChild(li);
    });
    ingredientsCell.appendChild(ingredientsList);
    
    // Price cell
    const priceCell = document.createElement("td");
    const priceDiv = document.createElement("div");
    priceDiv.className = "dish-price";
    priceDiv.textContent = `$${dish.price.toFixed(2)}`;
    priceCell.appendChild(priceDiv);
    
    // Append all cells to row
    row.appendChild(imageCell);
    row.appendChild(nameCell);
    row.appendChild(descCell);
    row.appendChild(categoryCell);
    row.appendChild(ingredientsCell);
    row.appendChild(priceCell);
    
    menuBody.appendChild(row);
  });
}

// === Filter menu by category ===
function filterMenu() {
  const selectedCategory = categoryFilter.value;
  const rows = menuBody.querySelectorAll("tr");
  
  rows.forEach(row => {
    const rowCategory = row.getAttribute("data-category");
    
    if (selectedCategory === "all" || rowCategory === selectedCategory) {
      row.classList.remove("hidden");
    } else {
      row.classList.add("hidden");
    }
  });
}

categoryFilter.addEventListener("change", filterMenu);
loadMenu();