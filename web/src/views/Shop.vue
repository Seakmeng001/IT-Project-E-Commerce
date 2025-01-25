<template>
  <section class="shop_section layout_padding">
    <div class="container">
      <!-- Section Heading -->
      <div class="heading_container heading_center mb-4">
        <h2>Products List</h2>
      </div>

      <!-- Products Grid -->
      <div class="row g-4">
        <div
          v-for="(product, index) in staticProducts"
          :key="index"
          class="col-sm-6 col-md-4 col-lg-3"
        >
          <div class="product-card">
            <div class="img-box position-relative">
              <img :src="product.image" alt="Product Image" />
              <span v-if="product.isNew" class="badge badge-new">New</span>
              <span v-if="product.isBestSeller" class="badge badge-best">Best Seller</span>
            </div>
            <div class="product-details">
              <h6 class="product-name">{{ product.name }}</h6>
              <div class="rating">
                <span v-for="star in 5" :key="star" class="star" :class="{ filled: star <= product.rating }">★</span>
                <span class="rating-count">({{ product.ratingCount }})</span>
              </div>
              <div class="price">
                <strong>{{ product.price }}$</strong>
              </div>
              <button
                class="btn btn-primary btn-cart"
                @click="addToCart(product.id)"
              >
              <i class="fa fa-shopping-cart icon" aria-hidden="true"></i> Add to cart
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
import img1 from "../../src/assets/images/3ce2.jpg";
import img2 from "../../src/assets/images/romand2.jpg"
import img3 from "../../src/assets/images/3ce1.jpg"

export default {
  name: "Shop",
  data() {
    return {
      staticProducts: [
        {
          id: 1,
          name: "Rom&nd Juicy Lasting Tint Israel",
          image: img2,
          price: 23.95,
          rating: 4,
          ratingCount: 350,
          isNew: true,
          isBestSeller: false,
        },
        {
          id: 2,
          name: "3CE Velvet Lip Tint",
          image: img1,
          price: 19.99,
          rating: 5,
          ratingCount: 420,
          isNew: false,
          isBestSeller: true,
        },
        {
          id: 3,
          name: "Rom&nd Glow Glasting",
          image: img2,
          price: 14.95,
          rating: 5,
          ratingCount: 290,
          isNew: false,
          isBestSeller: false,
        },
        {
          id: 4,
          name: "3Ce Tints and shades",
          image: img3,
          price: 29.95,
          rating: 3,
          ratingCount: 120,
          isNew: false,
          isBestSeller: true,
        },
        {
          id: 5,
          name: "Rom&nd Juicy Lasting Tint Israel",
          image: img2,
          price: 23.95,
          rating: 4,
          ratingCount: 350,
          isNew: true,
          isBestSeller: false,
        },
        {
          id: 6,
          name: "3CE Velvet Lip Tint",
          image: img1,
          price: 19.99,
          rating: 5,
          ratingCount: 420,
          isNew: false,
          isBestSeller: true,
        },
        {
          id: 7,
          name: "Rom&nd Glow Glasting",
          image: img2,
          price: 14.95,
          rating: 5,
          ratingCount: 290,
          isNew: false,
          isBestSeller: false,
        },
        {
          id: 8,
          name: "3Ce Tints and shades",
          image: img3,
          price: 29.95,
          rating: 3,
          ratingCount: 120,
          isNew: false,
          isBestSeller: true,
        },
      ],
    };
  },
  methods: {
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
  row-gap: 20px; /* Space between rows */
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
  background-color: #5a9adf;
  color: #fff;
  border: none;
  border-radius: 5px;
  padding: 8px 15px;
  cursor: pointer;
}

.btn-cart:hover {
  background-color: gray;
}

.icon {
  margin-right: 8px;
}
</style>