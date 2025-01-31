<template>
  <div class="container my-5">
    <div class="heading_container mb-4">
      <h2>Cart</h2>
    </div>
    <table class="table">
      <thead>
        <tr>
          <th scope="col"></th>
          <th scope="col">Product</th>
          <th scope="col">Price</th>
          <th scope="col">Quantity</th>
          <th scope="col">Subtotal</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(item, index) in cartItems" :key="item.id">
          <td>
            <button @click="removeItem(item.id, index)" class="btn text-danger">&times;</button>
          </td>
          <td>
            <img :src="'http://127.0.0.1:8000' + item.image" alt="image" style="height: 70px; width: 70px; object-fit: cover"/>
            <span class="ms-2">{{ item.name }}</span>
          </td>
          <td>${{ item.price.toFixed(2) }}</td>
          <td>
            <input type="number" v-model.number="item.quantity" min="1" class="form-control w-25"
              @change="updateQuantity(item.id, item.product_id, item.quantity)" />
          </td>
          <td>${{ (item.price * item.quantity).toFixed(2) }}</td>
        </tr>
      </tbody>
    </table>

    <div class="d-flex justify-content-between mt-4">
      <div class="d-flex align-items-center">
        <!-- <input type="text" v-model="couponCode" placeholder="Coupon code" class="form-control me-2 w-50"/> -->
        <!-- <button @click="applyCoupon" class="input-box ml-2">Apply Coupon</button> -->
      </div>
      <button class="input-box btn-secondary" @click="updateCart">Update Cart</button>
    </div>

    <div class="card mt-4 p-4 w-50 ms-auto">
      <h4>Total</h4>
      <p><strong>Subtotal:</strong> ${{ totalPrice }}</p>
      <p><strong>Shipping:</strong> Free shipping</p>
      <button class="input-box w-100 mt-3">Proceed to checkout</button>
    </div>
  </div>
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
  data() {
    return {
      cartItems: [],
      couponCode: "",
    };
  },
  computed: {
    totalPrice() {
      return this.cartItems.reduce((total, item) => total + item.price * item.quantity, 0).toFixed(2);
    },
  },
  methods: {
    async removeItem(cartID, index) {
      const token = getCookie("token");

      if (!token) {
        console.error("No token found. Please log in.");
        return;
      }

      try {
        await axios.delete(`http://127.0.0.1:8000/api/v1/cart/${cartID}`, {
          headers: { Authorization: `Bearer ${token}` },
          withCredentials: true,
        });

        this.cartItems.splice(index, 1);
        console.log("Item removed from cart:", cartID);
      } catch (error) {
        console.error("Error removing item from cart:", error);
      }
    },

    async updateQuantity(cartID, productID, quantity) {
      if (quantity > 0) {
        try {
          const token = getCookie("token");
          const response = await axios.put(
            `http://127.0.0.1:8000/api/v1/cart/${cartID}`,
            { product_id: productID, quantity: quantity },
            {
              headers: {
                Authorization: `Bearer ${token}`,
                "Content-Type": "application/json",
              },
              withCredentials: true,
            }
          );
          console.log("Cart updated:", response.data);
        } catch (err) {
          console.log("Error updating cart:", err);
        }
      }
    },

    async fetchProductsInCart() {
      const token = getCookie("token");

      if (!token) {
        console.error("No token found. Please log in.");
        return;
      }

      try {
        const response = await axios.get("http://127.0.0.1:8000/api/v1/cart", {
          headers: { Authorization: `Bearer ${token}` },
          withCredentials: true,
        });
        this.cartItems = response.data;
        console.log("Fetched cart items:", response.data);
      } catch (error) {
        console.error("Error fetching products:", error);
      }
    },
  },
  mounted() {
    this.fetchProductsInCart();
  },
};
</script>

<style scoped>
.table img {
  border-radius: 5px;
}

.input-box {
  background-color: #f16179;
  border: 1px solid #f16179;
  color: #fff;
  padding: 6px 12px;
  border-radius: 5px;
  cursor: pointer;
}

.input-box:hover {
  background-color: #e2506a;
}
</style>
