<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import Welcome from "@/Components/Welcome.vue";
import { reactive, onMounted } from "vue";
import axios from "axios";

const state = reactive({
  products: [],
  product: newProduct(),
  modalForProduct: null,
  deleting_product: null,
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

  state.modalForProduct = new window.bootstrap.Modal("#modalForProduct", {});
  state.modalForProduct._element.addEventListener("hide.bs.modal", () => {});
  getProducts();
});

function newProduct() {
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

function addAndUpdateProduct() {
  console.log(state.product);
  return axios
    .post(`/create-products`, {
      name: state.product.name,
      description: state.product.description,
      price: state.product.price,
      id: state.product.id,
    })
    .then((res) => {
      getProducts();
      state.modalForProduct.hide();
    })
    .catch((e) => {
      console.log(e);
    });
}

function addNewProduct() {
  state.product = newProduct();
  state.modalForProduct.show();
}

function deleteAction(product) {
  state.deleting_product = product;
  state.modalProductDeleteConfirm.show();
}

function editProduct(product) {
  state.product = product;
  state.modalForProduct.show();
}

function deleteProduct() {
  if (!state.deleting_product || !state.deleting_product.id) {
    return;
  }

  return axios
    .delete(`/delete-products/${state.deleting_product.id}`)
    .then((res) => {
      getProducts();
      state.modalProductDeleteConfirm.hide();
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
            Product Management
          </h2>
        </div>
        <div class="col-md-6">
          <button
            style="float: right"
            type="button"
            class="btn btn-outline-primary"
            @click="addNewProduct"
          >
            Add new product
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
            <tr v-for="product in state.products" :key="product.id">
              <td></td>
              <td>{{ product.name }}</td>
              <td>{{ product.description }}</td>
              <td>{{ product.price }}</td>
              <td>
                <button
                  type="button"
                  @click="editProduct(product)"
                  class="btn btn-outline-success"
                  style="margin-right: 11px; font-size: 13px"
                >
                  <i class="mdi mdi-pencil"></i>
                </button>
                <button
                  @click="deleteAction(product)"
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
            Are you sure you want to delete this product?
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
      id="modalForProduct"
      class="modal fade"
      tabindex="-1"
      role="dialog"
      aria-labelledby="modalForProduct_label"
      aria-hidden="true"
    >
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header modal-colored-header bg-primary">
            <h4 class="modal-title" id="modalForProduct_label">
              Modal Heading
            </h4>
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
                v-model="state.product.name"
                type="text"
              />
            </div>

            <div class="mb-3">
              <label for="product_description" class="form-label"
                >Description</label
              >
              <input
                v-model="state.product.description"
                type="text"
                id="product_description"
                class="form-control"
              />
            </div>

            <div class="mb-3">
              <label for="product_description" class="form-label">Price</label>
              <input
                v-model="state.product.price"
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
              @click="addAndUpdateProduct"
            >
              {{ state.product.id ? "Update" : "Save" }}
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
