<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import Welcome from "@/Components/Welcome.vue";
import { reactive, onMounted } from "vue";
import axios from "axios";
import { notify } from "@kyvg/vue3-notification";

const state = reactive({
  createdOrdersList: [],
  payment: newPayment(),
  modalForPay: null,
  deletingProduct: null,
  selectedOrder: null,
  modalProductDeleteConfirm: null,
});

onMounted(() => {
  state.modalProductDeleteConfirm = new window.bootstrap.Modal(
    "#modalProductDeleteConfirm",
    {}
  );
  state.modalProductDeleteConfirm._element.addEventListener(
    "hide.bs.modal",
    () => {}
  );

  state.modalForPay = new window.bootstrap.Modal("#modalForPay", {});
  state.modalForPay._element.addEventListener("hide.bs.modal", () => {});
  getUserOrders();
});

function newPayment() {
  return {
    payment_type: "card",
    amount: null,
  };
}

function getUserOrders() {
  axios
    .get("/get-user-orders")
    .then((res) => {
      state.createdOrdersList = res.data.orders;
    })
    .catch((e) => {});
}

function pay() {
  return axios
    .post(`/pay`, {
      amount: state.payment.amount,
      payment_type: state.payment.payment_type,
      order_id: state.selectedOrder.id,
    })
    .then((res) => {
      state.modalForPay.hide();
      getUserOrders();
    })
    .catch((e) => {
      console.log(e);
    });
}

function addNewProduct() {
  state.payment = newPayment();
  state.modalForPay.show();
}

function deleteAction(payment) {
  state.deletingProduct = payment;
  state.modalProductDeleteConfirm.show();
}

function editProduct(payment) {
  state.payment = payment;
  state.modalForPay.show();
}

function paymentAction(order) {
  state.selectedOrder = order;
  state.modalForPay.show();
}

function deleteProduct() {
  if (!state.deletingProduct || !state.deletingProduct.id) {
    return;
  }

  return axios
    .delete(`/delete-products/${state.deletingProduct.id}`)
    .then((res) => {
      getProducts();
      state.modalProductDeleteConfirm.hide();
      state.deletingProduct = null;
    })
    .catch((e) => {
      console.log(e);
    });
}

function checkValidAmount() {
  var amountToPay =
    state.selectedOrder.product.price - state.selectedOrder.paid_amount;

  if (state.payment.amount > amountToPay) {
    state.payment.amount = amountToPay;
  }
}
</script>


<template>
  <AppLayout title="Dashboard">
    <template #header>
      <div class="row">
        <div class="col-md-6">
          <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Order Logs
          </h2>
        </div>
      </div>
    </template>

    <div class="container">
      <div class="row justify-content-md-center">
        <table class="table table-striped table-hover">
          <thead>
            <tr>
              <th></th>
              <th scope="col">Date</th>
              <th scope="col">Order Number</th>
              <th scope="col">Product Name</th>
              <th scope="col">Product Price</th>
              <th scope="col">Paid Amount</th>
              <th scope="col">Action</th>
            </tr>
          </thead>

          <tbody>
            <tr v-for="order in state.createdOrdersList" :key="order.id">
              <td></td>
              <td>{{ order.order_date }}</td>
              <td>{{ order.order_no }}</td>
              <td>{{ order.product.name }}</td>
              <td>{{ order.product.price }}</td>
              <td>{{ order.paid_amount }}</td>

              <td>
                <button
                  v-if="order.paid_amount != order.product.price"
                  @click="paymentAction(order)"
                  type="button"
                  class="btn btn-outline-primary"
                >
                  Pay
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <div
      id="modalProductDeleteConfirm"
      class="modal fade"
      tabindex="-1"
      role="dialog"
      aria-labelledby="modalProductDeleteConfirm_label"
      aria-hidden="true"
    >
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header modal-colored-header bg-primary">
            <h4 class="modal-title" id="modalProductDeleteConfirm_label">
              Delete Product
            </h4>
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal"
              aria-hidden="true"
            ></button>
          </div>
          <div class="modal-body">
            Are you sure you want to delete this payment?
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-outline-light"
              data-bs-dismiss="modal"
            >
              Close
            </button>
            <button
              type="button"
              class="btn btn-outline-primary"
              @click="deleteProduct"
            >
              Confirm
            </button>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>

    <!-- /.modal -->

    <div
      id="modalForPay"
      class="modal fade"
      tabindex="-1"
      role="dialog"
      aria-labelledby="modalForProduct_label"
      aria-hidden="true"
    >
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header modal-colored-header bg-primary">
            <h4 class="modal-title" id="modalForProduct_label">Payment</h4>
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal"
              aria-hidden="true"
            ></button>
          </div>
          <div class="modal-body">
            <div class="input-group mb-6">
              <label for="product_description" class="form-label"
                >Payment type</label
              >
              <select
                class="custom-select"
                id="inputGroupSelect01"
                style="width: 100%"
                v-model="state.payment.payment_type"
              >
                <option value="card">Card</option>
                <option value="cash">Cash</option>
              </select>
            </div>

            <div class="mb-3">
              <label for="product_description" class="form-label">Amount</label>
              <input
                v-model="state.payment.amount"
                type="text"
                id="product_description"
                class="form-control"
                @change="checkValidAmount()"
              />
            </div>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-outline-light"
              data-bs-dismiss="modal"
            >
              Close
            </button>
            <button type="button" class="btn btn-outline-primary" @click="pay">
              Pay
            </button>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
  </AppLayout>
</template>
