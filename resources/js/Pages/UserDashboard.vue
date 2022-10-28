<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import Welcome from "@/Components/Welcome.vue";
import { reactive, onMounted } from "vue";
import axios from "axios";
import { notify } from "@kyvg/vue3-notification";

const state = reactive({
  products: [],
  order: newOrder(),
  modalForOrder: null,
  createOrderProduct: null,
  modalOrderConfirm: null,
});

onMounted(() => {
  state.modalOrderConfirm = new window.bootstrap.Modal(
    "#modalOrderConfirm",
    {}
  );
  state.modalOrderConfirm._element.addEventListener("hide.bs.modal", () => {});

  state.modalForOrder = new window.bootstrap.Modal("#modalForOrder", {});
  state.modalForOrder._element.addEventListener("hide.bs.modal", () => {});
  getProducts();
});

function newOrder() {
  return {
    name: null,
    description: null,
    price: null,
    id: null,
  };
}

function getProducts() {
  axios
    .get("/get-products")
    .then((res) => {
      state.products = res.data.product;
    })
    .catch((e) => {});
}

function createNewOrder() {
  return axios
    .post(`/create-order`, {
      product_id: state.createOrderProduct.id,
      price: state.createOrderProduct.price,
    })
    .then((res) => {
      state.modalOrderConfirm.hide();
      state.createOrderProduct = null;
    })
    .catch((e) => {
      console.log(e);
    });
}

function addNewOrder() {
  state.order = newOrder();
  state.modalForOrder.show();
}

function createOrderConfirmation(order) {
  state.createOrderProduct = order;
  state.modalOrderConfirm.show();
}

function editOrder(order) {
  state.order = order;
  state.modalForOrder.show();
}

function deleteOrder() {
  if (!state.createOrderProduct || !state.createOrderProduct.id) {
    return;
  }

  return axios
    .delete(`/delete-orders/${state.createOrderProduct.id}`)
    .then((res) => {
      getProducts();
      state.modalOrderConfirm.hide();
      state.createOrderProduct = null;
    })
    .catch((e) => {
      console.log(e);
    });
}
</script>


<template>
  <div class="container">
    <div class="row justify-content-start">
      <!-- v-for="order in state.orders" :key="order.id" -->
      <div class="col-md-3" v-for="product in state.products" :key="product.id">
        <div class="card" style="margin: 12px">
          <div class="card-body">
            <h5 class="card-title">{{ product.name }}</h5>
            <p class="card-text">
              {{ product.description }}
            </p>

            <div class="row">
              <div
                class="col-md-6"
                style="padding: 9px; padding-left: 10px; font-weight: 600"
              >
                <h1 class="card-text">AED {{ product.price }}</h1>
              </div>
              <div class="col-md-6">
                <a
                  style="float: right"
                  @click="createOrderConfirmation(product)"
                  class="btn btn-primary"
                  >Create order</a
                >
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div
    id="modalOrderConfirm"
    class="modal fade"
    tabindex="-1"
    role="dialog"
    aria-labelledby="modalOrderConfirm_label"
    aria-hidden="true"
  >
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header modal-colored-header bg-primary">
          <h4 class="modal-title" id="modalOrderConfirm_label">
            Confirm order
          </h4>
          <button
            type="button"
            class="btn-close"
            data-bs-dismiss="modal"
            aria-hidden="true"
          ></button>
        </div>
        <div class="modal-body">
          Are you sure you want to confirm this order?
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
            @click="createNewOrder"
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
    id="modalForOrder"
    class="modal fade"
    tabindex="-1"
    role="dialog"
    aria-labelledby="modalForOrder_label"
    aria-hidden="true"
  >
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header modal-colored-header bg-primary">
          <h4 class="modal-title" id="modalForOrder_label">Modal Heading</h4>
          <button
            type="button"
            class="btn-close"
            data-bs-dismiss="modal"
            aria-hidden="true"
          ></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="product_title" class="form-label">name</label>
            <input
              id="product_title"
              class="form-control"
              v-model="state.order.name"
              type="text"
            />
          </div>

          <div class="mb-3">
            <label for="product_description" class="form-label"
              >Description</label
            >
            <input
              v-model="state.order.description"
              type="text"
              id="product_description"
              class="form-control"
            />
          </div>

          <div class="mb-3">
            <label for="product_description" class="form-label">Price</label>
            <input
              v-model="state.order.price"
              type="text"
              id="product_description"
              class="form-control"
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
          <button
            type="button"
            class="btn btn-outline-primary"
            @click="addAndUpdateOrder"
          >
            {{ state.order.id ? "Update" : "Save" }}
          </button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->
</template>
