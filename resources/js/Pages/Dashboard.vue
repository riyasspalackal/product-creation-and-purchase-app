<script setup>
import { Head, Link } from "@inertiajs/inertia-vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import UserDashboard from "./UserDashboard.vue";
import AdminDashboard from "./AdminDashboard.vue";
</script>

<template>
  <AppLayout title="Dashboard">
    <template #header>
      <div class="row">
        <div class="col-md-6">
          <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{
              $page.props.user.user_type_id == 1
                ? "Order Management"
                : "Products"
            }}
          </h2>
        </div>
      </div>
    </template>

    <AdminDashboard v-if="$page.props.user.user_type_id == 1"></AdminDashboard>
    <UserDashboard v-else></UserDashboard>
  </AppLayout>
</template>
