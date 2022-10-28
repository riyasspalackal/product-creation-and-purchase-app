<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import Welcome from "@/Components/Welcome.vue";
import { reactive, onMounted } from "vue";
import axios from "axios";
import { notify } from "@kyvg/vue3-notification";

const state = reactive({
  ordersLists: [],
  payment: newPayment(),
  modalForPay: null,
  deletingProduct: null,
  selectedOrder: null,
  modalProductDeleteConfirm: null,
  transactionHistories: [],
  perPage: 10,
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
    .get("/get-all-orders")
    .then((res) => {
      state.ordersLists = res.data.orders;
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

function transactionHistory(order) {
  state.currentOrder = order;
  axios
    .get("/transaction-history/" + order.id + "?per_page=" + state.perPage)
    .then((res) => {
      state.transactionHistories = [];
      state.transactionHistories = res.data.data;
      state.totalPage = res.data.total;
      state.modalForPay.show();
    })
    .catch((e) => {});
}

function checkValidAmount() {
  var amountToPay =
    state.selectedOrder.product.price - state.selectedOrder.paid_amount;

  if (state.payment.amount > amountToPay) {
    state.payment.amount = amountToPay;
  }
}

function handleScroll(el) {
  if (
    el.target.scrollTop + el.target.clientHeight + 2 >=
    el.target.scrollHeight
  ) {
    state.perPage += 3;
    if (state.totalPage > state.perPage) {
      transactionHistory(state.currentOrder);
    }
  }
}
</script>


<template>
  <div class="container">
    <div class="row justify-content-md-center">
      <table class="table table-striped table-hover">
        <thead>
          <tr>
            <th></th>
            <th scope="col">Date</th>
            <th scope="col">Order Number</th>
            <th scope="col">Customer Name</th>
            <th scope="col">Product Name</th>
            <th scope="col">Product Price</th>
            <th scope="col">Paid Amount</th>
            <th scope="col"></th>
          </tr>
        </thead>

        <tbody>
          <tr v-for="order in state.ordersLists" :key="order.id">
            <td></td>
            <td>{{ order.order_date }}</td>
            <td>{{ order.order_no }}</td>
            <td>{{ order.user.name }}</td>
            <td>{{ order.product.name }}</td>
            <td>AED {{ order.product.price }}</td>
            <td>
              <span v-if="order.paid_amount"> AED</span>
              <span v-else> - </span>
              {{ order.paid_amount }}
            </td>

            <td>
              <button
                @click="
                  state.perPage = 10;
                  transactionHistory(order);
                "
                type="button"
                class="btn btn-outline-primary"
              >
                Transaction History
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
    <div class="modal-dialog" style="max-width: 65%">
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
        <div
          class="container"
          style="overflow-y: scroll; height: 300px"
          v-on:scroll.passive="handleScroll($event)"
        >
          <div class="row justify-content-md-center">
            <table class="table table-striped table-hover">
              <thead>
                <tr>
                  <th></th>
                  <th scope="col">Date</th>
                  <th scope="col">Transaction Id</th>
                  <th scope="col">Payment Type</th>
                  <th scope="col">Amount</th>
                  <th scope="col"></th>
                </tr>
              </thead>

              <tbody>
                <tr
                  v-for="transaction in state.transactionHistories"
                  :key="transaction.id"
                >
                  <td></td>
                  <td>
                    {{
                      new Date(transaction.created_at).getDate() +
                      "/" +
                      new Date(transaction.created_at).getMonth() +
                      "/" +
                      new Date(transaction.created_at).getFullYear()
                    }}
                  </td>
                  <td>{{ transaction.transaction_id }}</td>
                  <td>{{ transaction.payment_type }}</td>
                  <td>{{ transaction.amount }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->
</template>
