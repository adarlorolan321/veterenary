<script setup>
import Layout from "@/layouts/default.vue";
import { ref } from "vue";
import { avatarText } from "@/@core/utils/formatters";
import { useCrud } from "@core/composable/useCrud";
import { usePage, Head, Link, useForm } from "@inertiajs/vue3";

const { props } = usePage();
const perPage = props.data && props.data.meta ? props.data.meta.per_page : 100;
const sort = "id";
const order = "desc";

const data = ref([
  {
    full_name: "John Doe",
    email: "4t6g9@example.com",
    start_date: "2022-01-01",
    salary: 500,
    age: 25,
    status: 1,
  },
]);

const fields = [
{
    title: "Owner",
    key: "user.name",
  },
  {
    title: "Name",
    key: "name",
  },
  {
    title: "Age",
    key: "age",
  },
  {
    title: "Gender",
    key: "gender",
  },
  {
    title: "Species",
    key: "type",
  },
  {
    title: "Weight",
    key: "weight",
  },
  {
    title: "DOB",
    key: "dob",
  },
];

const formObject = {
  name: "",
  age: "",
  gender: "",
  type: "",
  weight: "",
  dob: "",
};

const routeName = "pets.index";

let {
  paginatedData,
  form,
  createPromise,
  updatePromise,
  deletePromise,
  handleCreate,
  serverQuery,
  handleServerQuery,
  handleEdit,
  formState,
  handlePageChange,
} = useCrud({ formObject, routeName, perPage, sort, order });

const paginateTable = (pageIndex) => {
  handlePageChange(pageIndex);
};

const handlePerPageChange = (value) => {
  handleServerQuery("perPage", value);
};

const handleSearchChange = (value) => {
  handleServerQuery("query", value);
};
</script>

<script>
export default {
  name: "Staff",
  layout: (h, page) => h(Layout, [page]),
};
</script>

<template>
  <Head title="Pets" />
  <VCardText class="d-flex align-center flex-wrap gap-4">
    <!-- 👉 Rows per page -->
    <div class="d-flex align-center" style="width: 135px">
      <span class="text-no-wrap me-3">Show:</span>
      <VSelect
        v-model="serverQuery.perPage"
        density="compact"
        :items="[5, 10, 20, 50, 100]"
        @update:model-value="handlePerPageChange"
      />
    </div>

    <div class="me-3">
      <!-- 👉 Create invoice -->
      <!-- <VBtn prepend-icon="tabler-plus" :to="{ name: 'apps-invoice-add' }"> Create Organization </VBtn> -->
    </div>

    <VSpacer />

    <div class="d-flex align-center flex-wrap gap-4">
      <!-- 👉 Search  -->
      <div class="search-bar">
        <VTextField
          v-model="serverQuery.query"
          label="Search"
          placeholder="Search"
          density="compact"
          single-line
          @update:model-value="handleSearchChange"
        />
      </div>
    </div>
  </VCardText>

  <VDivider />

  <VTable height="550" class="text-no-wrap">
    <thead>
      <tr>
        <template
          :key="key"
          v-for="(field, key) in fields"
          :style="`min-width: ${field.minWidth}; width: ${field.width}`"
          @click="handleServerQuery('sort', field.field, field.sorting)"
          :serverQuery="serverQuery"
          :serverQueryKey="field.field"
          :sorting="field.sorting"
        >
          <th class="text-uppercase text-center">{{ field.title }}</th>
        </template>
      </tr>
    </thead>

    <tbody>
      <tr v-for="tableData in paginatedData.data" :key="tableData">
        <td class="text-center">
          {{ tableData.user.first_name }}   {{ tableData.user.last_name }}
        </td>
        <td>
          {{ tableData.name }}
        </td>
        <td class="text-center">
          {{ tableData.age }}
        </td>
        <td class="text-center">
          {{ tableData.gender }}
        </td>
        <td class="text-center">
          {{ tableData.type }}
        </td>
        <td class="text-center">
          {{ tableData.weight }}
        </td>
        <td class="text-center">
          {{ tableData.dob }}
        </td>
      </tr>
    </tbody>
    <tfoot v-show="!paginatedData.data.length">
      <tr>
        <td colspan="8" class="text-center text-body-1">No data available</td>
      </tr>
    </tfoot>
  </VTable>
  <VDivider />

  <!-- SECTION Pagination -->
  <VCardText class="d-flex align-center flex-wrap gap-4 py-3">
    <!-- 👉 Pagination meta -->
    <span class="text-sm text-disabled" v-if="paginatedData.meta.total">
      {{
        `Showing ${paginatedData.meta.from} to ${paginatedData.meta.to} of ${paginatedData.meta.total} entries`
      }}
    </span>

    <VSpacer />

    <!-- 👉 Pagination -->
    <VPagination
      v-model="paginatedData.meta.current_page"
      size="small"
      :total-visible="5"
      :length="paginatedData.meta.last_page"
      @update:model-value="paginateTable"
      @next="paginateTable"
      @prev="paginateTable"
    />
  </VCardText>
</template>
<style scoped></style>
