<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import Welcome from "@/Components/Welcome.vue";
import { reactive, onMounted } from "vue";
import axios from "axios";

const state = reactive({
  orders: [],
  order: newOrder(),
  modalForOrder: null,
  deleting_product: null,
  modalOrderDeleteConfirm: null,
});

onMounted(() => {
  state.modalOrderDeleteConfirm = new window.bootstrap.Modal(
    "#modalOrderDeleteConfirm",
    {}
  );
  state.modalOrderDeleteConfirm._element.addEventListener(
    "hide.bs.modal",
    () => {}
  );

  state.modalForOrder = new window.bootstrap.Modal("#modalForOrder", {});
  state.modalForOrder._element.addEventListener("hide.bs.modal", () => {});
  getorders();
});

function newOrder() {
  return {
    name: null,
    description: null,
    price: null,
    id: null,
  };
}

function getorders() {
  axios
    .get("/get-orders")
    .then((res) => {
      state.orders = res.data.order;
    })
    .catch((e) => {});
}

function addAndUpdateOrder() {
  console.log(state.order);
  return axios
    .post(`/create-orders`, {
      name: state.order.name,
      description: state.order.description,
      price: state.order.price,
      id: state.order.id,
    })
    .then((res) => {
      getorders();
      state.modalForOrder.hide();
    })
    .catch((e) => {
      console.log(e);
    });
}

function addNewOrder() {
  state.order = newOrder();
  state.modalForOrder.show();
}

function deleteAction(order) {
  state.deleting_product = order;
  state.modalOrderDeleteConfirm.show();
}

function editOrder(order) {
  state.order = order;
  state.modalForOrder.show();
}

function deleteOrder() {
  if (!state.deleting_product || !state.deleting_product.id) {
    return;
  }

  return axios
    .delete(`/delete-orders/${state.deleting_product.id}`)
    .then((res) => {
      getorders();
      state.modalOrderDeleteConfirm.hide();
      state.deleting_product = null;
    })
    .catch((e) => {
      console.log(e);
    });
}
</script>


<template>
  <AppLayout title="Dashboard">
    <template #header>
      <div class="row">
        <div class="col-md-6">
          <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Order Management
          </h2>
        </div>
        <div class="col-md-6">
          <button
            style="float: right"
            type="button"
            class="btn btn-outline-primary"
            @click="addNewOrder"
          >
            Add new order
          </button>
        </div>
      </div>
    </template>

    <div class="container">
      <div class="row justify-content-md-center">
        <table class="table table-striped table-hover">
          <thead>
            <tr>
              <th></th>
              <th scope="col">Name</th>
              <th scope="col">Description</th>
              <th scope="col">Price</th>
              <th scope="col">Action</th>
            </tr>
          </thead>

          <tbody>
            <tr v-for="order in state.orders" :key="order.id">
              <td></td>
              <td>{{ order.name }}</td>
              <td>{{ order.description }}</td>
              <td>{{ order.price }}</td>
              <td>
                <button
                  type="button"
                  @click="editOrder(order)"
                  class="btn btn-outline-success"
                  style="margin-right: 11px; font-size: 13px"
                >
                  <i class="mdi mdi-pencil"></i>
                </button>
                <button
                  @click="deleteAction(order)"
                  type="button"
                  class="btn btn-outline-danger"
                  style="margin-right: 11px; font-size: 13px"
                >
                  <i class="mdi mdi-delete"></i>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <div
      id="modalOrderDeleteConfirm"
      class="modal fade"
      tabindex="-1"
      role="dialog"
      aria-labelledby="modalOrderDeleteConfirm_label"
      aria-hidden="true"
    >
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header modal-colored-header bg-primary">
            <h4 class="modal-title" id="modalOrderDeleteConfirm_label">
              Delete Order
            </h4>
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal"
              aria-hidden="true"
            ></button>
          </div>
          <div class="modal-body">
            Are you sure you want to delete this order?
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
              @click="deleteOrder"
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
  </AppLayout>
</template>
