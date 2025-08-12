let uploadsData = [];

const productsContainer = document.querySelector(".products");
const searchInput = document.querySelector(".search");
const categoriesContainer = document.querySelector(".cats");

const displayUploads = (filteredUploads) => {
  productsContainer.innerHTML = filteredUploads
  .map(
    (upload) =>
    `<div class="product" data-id="${upload.id}">
    <img src="${upload.thumbnail_path}" alt="${upload.title}">
    <span class="name">${upload.title}</span>
    <div class="buttons">
    <a href="productDetail.php?id=${upload.id}" class="view-btn">View</a>
    <button class="download-btn" onclick="downloadFile('${upload.file_path}')">Download</button>
    </div>
    </div>`
    )
  .join("");
};

const setCategories = () => {
  const allCats = uploadsData.map((item) => item.category);
  const categories = [
    "All",
    ...allCats.filter((item, i) => allCats.indexOf(item) === i),
    ];

  categoriesContainer.innerHTML = categories
  .map((cat) => `<span class="cat">${cat}</span>`)
  .join("");

  categoriesContainer.addEventListener("click", (e) => {
    const selectedCat = e.target.textContent;
    selectedCat === "All"
    ? displayUploads(uploadsData)
    : displayUploads(uploadsData.filter((item) => item.category === selectedCat));
  });
};

searchInput.addEventListener("keyup", (e) => {
  const value = e.target.value.toLowerCase();
  value
  ? displayUploads(uploadsData.filter((item) => item.title.toLowerCase().includes(value)))
  : displayUploads(uploadsData);
});

// Function to handle file download
const downloadFile = (filePath) => {
  window.location.href = filePath;  // This will trigger a download in most browsers
};

document.addEventListener('DOMContentLoaded', () => {
  // Fetch data from the PHP backend
  fetch('fetchUploads.php')
  .then(response => response.json())
  .then(data => {
    uploadsData = data;
    displayUploads(uploadsData);
    setCategories();
  })
  .catch(error => {
    console.error('Error fetching data:', error);
  });
});
