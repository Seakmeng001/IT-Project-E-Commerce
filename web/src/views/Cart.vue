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
            <button @click="removeItem(index)" class="btn text-danger">&times;</button>
          </td>
          <td>
            <img :src="item.image" alt="image" style="height: 70px; width: 70px; object-fit: cover"/>
            <span class="ms-2">{{ item.name }}</span>
          </td>
          <td>${{ item.price.toFixed(2) }}</td>
          <td>
            <input type="number" v-model="item.quantity" min="1" class="form-control w-25" @change="updateQuantity(index, $event)"/>
          </td>
          <td>${{ (item.price * item.quantity).toFixed(2) }}</td>
        </tr>
      </tbody>
    </table>

    <div class="d-flex justify-content-between mt-4">
      <div class="d-flex align-items-center">
        <input type="text" v-model="couponCode" placeholder="Coupon code" class="form-control me-2 w-50"/>
        <button @click="applyCoupon" class="input-box ml-2">Apply Coupon</button>
      </div>
      <button class="input-box btn-secondary">Update Cart</button>
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
import img1 from "../../src/assets/images/Serum.webp";
import img2 from "../../src/assets/images/Suncream.webp";

export default {
  data() {
    return {
      cartItems: [
        {
          id: 1,
          name: "Anti-Aging Serum For Face Care",
          image: img1,
          price: 19.99,
          quantity: 1,
        },
        {
          id: 2,
          name: "Minimalist Spf 60 Sunscreen 50g",
          image: img2,
          price: 23.95,
          quantity: 2,
        },
      ],
      couponCode: "",
    };
  },
  computed: {
    totalPrice() {
      return this.cartItems.reduce((total, item) => total + item.price * item.quantity, 0).toFixed(2);
    },
  },
  methods: {
    removeItem(index) {
      this.cartItems.splice(index, 1);
    },
    updateQuantity(index, event) {
      const value = parseInt(event.target.value);
      if (value > 0) {
        this.cartItems[index].quantity = value;
      }
    },
    applyCoupon() {
      alert("Coupon applied: " + this.couponCode);
    },
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