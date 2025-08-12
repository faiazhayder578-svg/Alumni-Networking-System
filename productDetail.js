const productDetailContainer = document.querySelector('.product-detail');

// Extract product ID from URL
const urlParams = new URLSearchParams(window.location.search);
const productId = parseInt(urlParams.get("id"));

if (productId) {
  // Fetch product details from the server
  fetch(`fetchProductDetails.php?id=${productId}`)
  .then(response => response.json())
  .then(product => {
    if (product) {
        // Populate the product detail page with the fetched data
      productDetailContainer.innerHTML = `
      <div class="product-detail-content">
      <div class="product-image-container">
      <img src="${product.thumbnail_path}" alt="${product.title}" class="product-image" />
      </div>
      <div class="product-info">
      <h2 class="product-name">${product.title}</h2>
      <p><strong>Category:</strong> ${product.category}</p>
      <p><strong>Genre:</strong> ${product.genre}</p>
      <p><strong>Author:</strong> ${product.author}</p>
      <p><strong>Description:</strong> ${product.description}</p>
      <p><strong>Uploaded By:</strong> ${product.uploaded_by}</p>
      <p><strong>Uploaded On:</strong> ${new Date(product.timestamp).toLocaleDateString()}</p>
      <button class="download-btn" onclick="downloadFile('${product.file_path}')">Download</button>
      </div>
      </div>
      `;
    } else {
      productDetailContainer.innerHTML = `<p>Product not found.</p>`;
    }
  })
  .catch(error => {
    console.error('Error fetching product details:', error);
    productDetailContainer.innerHTML = `<p>Failed to load product details.</p>`;
  });
}

// Function to handle file download
const downloadFile = (filePath) => {
  // Trigger a download in most browsers by setting the file path as the window location
  window.location.href = filePath;
};
