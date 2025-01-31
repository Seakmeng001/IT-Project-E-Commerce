<template>
  <section class="shop_section layout_padding">
    <div class="container">
      <!-- Section Heading -->
      <div class="heading_container heading_center mb-4">
        <h2>Promotion</h2>
      </div>
      <!-- Products Grid -->
      <div class="row g-4">
        <div
          v-for="(product, index) in paginatedProducts"
          :key="index"
          class="col-sm-6 col-md-4 col-lg-3"
        >
          <div class="product-card">
            <div class="img-box position-relative">
              <img :src="product.image" alt="Product Image" />
              <span
                v-if="product.discountPercentage"
                class="badge badge-discount"
              >
                -{{ product.discountPercentage }}%
              </span>
            </div>
            <div class="product-details">
              <h6 class="product-name">{{ product.name }}</h6>
              <div class="rating">
                <span
                  v-for="star in 5"
                  :key="star"
                  class="star"
                  :class="{ filled: star <= product.rating }"
                >
                  ★
                </span>
                <span class="rating-count">({{ product.ratingCount }})</span>
              </div>
              <div class="price">
                <span
                  v-if="product.discountPercentage"
                  class="original-price"
                >
                  {{ product.price }}$
                </span>
                <strong>
                  {{
                    product.discountPercentage
                      ? (product.price * (1 - product.discountPercentage / 100)).toFixed(2)
                      : product.price
                  }}$
                </strong>
              </div>
              <button
                class="btn btn-primary btn-cart"
                @click="addToCart(product.id)"
              >
                <i class="fa fa-shopping-cart icon" aria-hidden="true"></i> Add
                to cart
              </button>
            </div>
          </div>
        </div>
      </div>
      <!-- Pagination -->
      <div class="mt-4 d-flex justify-content-center">
        <ul class="pagination pagination-rounded mb-0">
          <li class="page-item">
            <button
              class="page-link"
              @click="changePage(currentPage - 1)"
              :disabled="currentPage === 1"
            >
              Previous
            </button>
          </li>
          <li
            v-for="page in totalPages"
            :key="page"
            :class="{ 'page-item': true, active: page === currentPage }"
          >
            <button class="page-link" @click="changePage(page)">
              {{ page }}
            </button>
          </li>
          <li class="page-item">
            <button
              class="page-link"
              @click="changePage(currentPage + 1)"
              :disabled="currentPage === totalPages"
            >
              Next
            </button>
          </li>
        </ul>
      </div>
    </div>
  </section>
</template>

<script>
import img1 from "../../src/assets/images/Serum.webp";
import img2 from "../../src/assets/images/Suncream.webp";
import img3 from "../../src/assets/images/Cetaphil3.jpg";
import img4 from "../../src/assets/images/Romand3.jpg";
import img5 from "../../src/assets/images/3ce3.jpg";

export default {
  name: "Shop",
  data() {
    return {
      products: [
        {
          id: 1,
          name: "Minimalist Spf 60 Sunscreen 50g",
          image: img2,
          price: 23.95,
          rating: 4,
          ratingCount: 350,
          discountPercentage: 20,
        },
        {
          id: 2,
          name: "Anti-Aging Serum For Face Care",
          image: img1,
          price: 19.99,
          rating: 5,
          ratingCount: 420,
          discountPercentage: 10,
        },
        {
          id: 3,
          name: "Rom&nd Glow Glasting",
          image: img4,
          price: 14.95,
          rating: 5,
          ratingCount: 290,
          discountPercentage: 15,
        },
        {
          id: 4,
          name: "Mattifying Ace Moisturizer",
          image: img3,
          price: 29.95,
          rating: 3,
          ratingCount: 120,
          discountPercentage: 20,
        },
        {
          id: 5,
          name: "3CE Syrus Layering Tint",
          image: img5,
          price: 14.95,
          rating: 5,
          ratingCount: 290,
          discountPercentage: 5,
        },
        {
          id: 6,
          name: "Mattifying Ace Moisturizer",
          image: img3,
          price: 29.95,
          rating: 3,
          ratingCount: 120,
          discountPercentage: 25,
        },
        {
          id: 7,
          name: "Anti-Aging Serum For Face Care",
          image: img1,
          price: 19.99,
          rating: 5,
          ratingCount: 420,
          discountPercentage: 15,
        },
        {
          id: 8,
          name: "Mattifying Ace Moisturizer",
          image: img3,
          price: 29.95,
          rating: 3,
          ratingCount: 120,
          discountPercentage: 35,
        },
        {
          id: 9,
          name: "3CE Syrus Layering Tint",
          image: img5,
          price: 14.95,
          rating: 5,
          ratingCount: 290,
          discountPercentage: 45,
        },
      ],
      itemsPerPage: 8,
      currentPage: 1,
    };
  },
  computed: {
    // Calculate the paginated products
    paginatedProducts() {
      const start = (this.currentPage - 1) * this.itemsPerPage;
      const end = this.currentPage * this.itemsPerPage;
      return this.products.slice(start, end);
    },
    // Calculate the total number of pages
    totalPages() {
      return Math.ceil(this.products.length / this.itemsPerPage);
    },
  },
  methods: {
    // Change page
    changePage(page) {
      if (page >= 1 && page <= this.totalPages) {
        this.currentPage = page;
      }
    },
    // Add product to the cart
    addToCart(productId) {
      alert(`Product with ID ${productId} added to cart!`);
    },
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

.row {
  row-gap: 20px;
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

.badge-discount {
  background-color: #28a745;
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

.original-price {
  text-decoration: line-through;
  color: #999;
  font-size: 14px;
  margin-right: 8px;
}

.btn-cart {
  background-color: #f16179;
  border: 1px solid #f16179;
  color: #fff;
  border-radius: 5px;
  padding: 8px 15px;
  cursor: pointer;
}

.btn-cart:hover {
  background-color: transparent;
  color: #f16179;
}

.icon {
  margin-right: 8px;
}
</style>