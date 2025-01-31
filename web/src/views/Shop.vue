<template>
  <section class="shop_section layout_padding">
    <div class="container">
      <!-- Section Heading -->
      <div class="heading_container heading_center mb-4">
        <h2>Products List</h2>
      </div>

      <!-- Loading Spinner -->
      <div v-if="loading" class="loading-spinner">
        <span>Loading...</span>
      </div>

      <!-- Alert Messages -->
      <div v-if="successMessage" class="alert alert-success mt-4">
        {{ successMessage }}
      </div>
      <div v-if="errorMessage" class="alert alert-danger mt-4">
        {{ errorMessage }}
      </div>

      <!-- Products Grid -->
      <div class="row g-4">
        <div
          v-for="(product, index) in products"
          :key="index"
          class="col-sm-6 col-md-4 col-lg-3"
        >
          <div class="product-card">
            <div class="img-box position-relative">
              <img
                :src="'http://127.0.0.1:8000' + product.image"
                alt="Product Image"
              />
              <span v-if="product.isNew" class="badge badge-new">New</span>
              <span v-if="product.isBestSeller" class="badge badge-best">Best Seller</span>
            </div>
            <div class="product-details">
              <h6 class="product-name">{{ product.name }}</h6>
              <div class="rating">
                <span
                  v-for="star in 5"
                  :key="star"
                  class="star"
                  :class="{ filled: star <= product.rating }"
                  >★</span
                >
                <span class="rating-count">({{ product.ratingCount }})</span>
              </div>
              <div class="price">
                <strong>{{ product.price }}$</strong>
              </div>
              <button
                type="submit"
                class="btn btn-primary btn-cart"
                @click="addToCart(product.id)"
              >
                Add to Cart
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Error Message -->
      <div v-if="errorMessage" class="alert alert-danger mt-4">
        {{ errorMessage }}
      </div>
    </div>
  </section>
</template>

<script>
import axios from "axios";

// Function to get cookie by name
function getCookie(name) {
  const value = `; ${document.cookie}`;
  const parts = value.split(`; ${name}=`);
  if (parts.length === 2) return parts.pop().split(";").shift();
  return null;
}

export default {
  name: "Shop",
  data() {
    return {
      products: [],
      errorMessage: "",
      successMessage: "",
      loading: false,
    };
  },
  methods: {
    // Fetch products from API
    async fetchProducts() {
      this.loading = true; // Set loading to true when fetching products
      const token = getCookie("token");

      if (!token) {
        this.errorMessage = "No token found. Please log in.";
        this.loading = false;
        return;
      }

      try {
        const response = await axios.get("http://127.0.0.1:8000/api/v1/product", {
          headers: { Authorization: `Bearer ${token}` },
          withCredentials: true,
        });

        this.products = response.data.data;
      } catch (error) {
        this.handleError(error);
      } finally {
        this.loading = false; // Set loading to false once data is fetched or error occurs
      }
    },

    // Handle error messages
    handleError(error) {
      if (error.response) {
        this.errorMessage = error.response.data.message || "An error occurred.";
      } else if (error.request) {
        this.errorMessage = "No response from the server.";
      } else {
        this.errorMessage = "An unknown error occurred.";
      }

      // Hide error message after 3 seconds
      setTimeout(() => {
        this.errorMessage = "";
      }, 3000);
    },

    // Add product to cart with success/error alert
    async addToCart(productId) {
      const quantity = 1;
      const token = getCookie("token");

      if (!token) {
        this.errorMessage = "No token found. Please log in.";
        return;
      }

      try {
        const response = await axios.post(
          "http://127.0.0.1:8000/api/v1/cart/",
          { product_id: productId, quantity: quantity },
          {
            headers: {
              Authorization: `Bearer ${token}`,
              "Content-Type": "application/json",
            },
            withCredentials: true,
          }
        );

        console.log("Product added to cart:", response.data);
        this.successMessage = "Product added to cart successfully!";

        // Hide the success message after 3 seconds
        setTimeout(() => {
          this.successMessage = "";
        }, 3000);
      } catch (error) {
        this.handleError(error);
      }
    },
  },
  mounted() {
    this.fetchProducts();
  },
};
</script>

<style scoped>
/* General Section Styling */
.shop_section {
  padding: 40px 0;
}

.heading_center h2 {
  margin-bottom: 20px;
}

/* Loading Spinner */
.loading-spinner {
  text-align: center;
  font-size: 20px;
  color: #007bff;
  margin-top: 20px;
}

/* Alert Messages */
.alert {
  text-align: center;
  font-size: 16px;
  padding: 10px;
  border-radius: 5px;
}

.alert-success {
  background-color: #d4edda;
  color: #155724;
}

.alert-danger {
  background-color: #f8d7da;
  color: #721c24;
}

/* Product Card Styling */
.product-card {
  border: 1px solid #ddd;
  border-radius: 10px;
  overflow: hidden;
  text-align: center;
  background: #fff;
  transition: transform 0.3s;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.product-card:hover {
  transform: translateY(-5px);
}

.img-box {
  position: relative;
  padding: 20px;
}

.img-box img {
  max-width: 100%;
  height: auto;
  object-fit: cover;
}

.badge {
  position: absolute;
  top: 10px;
  left: 10px;
  padding: 5px 10px;
  border-radius: 5px;
  font-size: 12px;
  color: #fff;
}

.badge-new {
  background-color: #f00;
}

.badge-best {
  background-color: #ff9800;
}

.product-details {
  padding: 10px;
}

.product-name {
  font-size: 14px;
  font-weight: bold;
  margin-bottom: 5px;
}

.rating {
  font-size: 14px;
  color: #ffc107;
  margin-bottom: 5px;
}

.star {
  color: #ddd;
}

.star.filled {
  color: #ffc107;
}

.rating-count {
  font-size: 12px;
  color: #999;
  margin-left: 5px;
}

.price {
  font-size: 16px;
  color: #000;
  font-weight: bold;
  margin-bottom: 10px;
}

.btn-cart {
  background-color: #007bff;
  color: #fff;
  border: none;
  border-radius: 5px;
  padding: 8px 15px;
  cursor: pointer;
}

.btn-cart:hover {
  background-color: #0056b3;
}
</style>
