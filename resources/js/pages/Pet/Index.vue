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
const userList = props.userList;

const petSpecies = ref([
  "Dogs",
  "Cats",
  "Fish",
  "Birds",
  "Hamsters",
  "Guinea Pigs",
  "Rabbits",
  "Reptiles",
  "Amphibians",
  "Gerbils",
  "Ferrets",
  "Chinchillas",
  "Hedgehogs",
  "Tarantulas",
  "Hermit Crabs",
  "Sugar Gliders",
  "Mice",
  "Rats",
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
  {
    title: "Action",
    key: "action",
  },
];

const formObject = {
  user_id: "",
  name: "",
  age: "",
  gender: "",
  type: "",
  weight: "",
  dob: "",
  breed: "",
};

const routeName = "pets";
let {
  isLoadingComponents,
  paginatedData,
  form,
  createPromise,
  updatePromise,
  deletePromise,
  handleCreate,
  isDialogVisible,
  serverQuery,
  handleServerQuery,
  handleEdit,
  formState,
} = useCrud(formObject, routeName);

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

  <VDialog v-model="isDialogVisible" class="v-dialog-sm p-5">
    <VCard title="Add Pet">
      <div class="container">
        <div class="form-group mb-3 mx-3">
          <label for="">Pet Owner<span class="required">*</span></label>
          <VSelect
            v-model="form.user_id"
            :items="userList"
            item-title="first_name"
            item-value="id"
            @update:modelValue="form.clearErrors('user_id')"
            placeholder="Select Type"
            :class="{ 'is-invalid': form.errors.user_id }"
          >
          </VSelect>
          <div class="invalid-feedback">
            {{ form.errors.user_id }}
          </div>
        </div>
        <div class="form-group mb-3 mx-3">
          <VTextField
            v-model="form.name"
            label="Pet Name"
            :error-messages="form.errors.name"
          />
        </div>
        <div class="form-group mb-3 mx-3">
          <VTextField
            v-model="form.age"
            label="Pet Age"
            :error-messages="form.errors.age"
          />
        </div>
        <div class="form-group mb-3 mx-3">
          <VSelect
            v-model="form.type"
            :items="petSpecies"
            @update:modelValue="form.clearErrors('type')"
            placeholder="Pet Species"
            :class="{ 'is-invalid': form.errors.type }"
          >
          </VSelect>
        </div>
        <div class="form-group mb-3 mx-3">
          <VTextField
            v-model="form.breed"
            label="Pet Breed"
            :error-messages="form.errors.breed"
          />
        </div>
        <div class="form-group mb-3 mx-3">
          <VTextField
            v-model="form.dob"
            label="Pet DOB"
            :error-messages="form.errors.dob"
          />
        </div>
        <div class="form-group mb-3 mx-3">
          <VTextField
            v-model="form.gender"
            label="Pet Gender"
            :error-messages="form.errors.gender"
          />
        </div>
        <div class="form-group mb-3 mx-3">
          <VTextField
            v-model="form.weight"
            label="Pet Weight"
            :error-messages="form.errors.weight"
          />
        </div>
      </div>

      <VCardText class="d-flex justify-end flex-wrap gap-3">
        <VBtn
          color="secondary"
          variant="tonal"
          @click="isDialogVisible = false"
        >
          Cancel
        </VBtn>
        <VBtn
          class="btn btn-primary"
          @click="createPromise"
          v-if="formState == 'create'"
        >
          <span
            v-if="form.processing"
            class="spinner-border me-1"
            role="status"
            aria-hidden="true"
          ></span>
          Save
        </VBtn>
        <VBtn
          class="btn btn-primary"
          @click="updatePromise"
          v-if="formState == 'update'"
        >
          <span
            v-if="form.processing"
            class="spinner-border me-1"
            role="status"
            aria-hidden="true"
          ></span>
          Save changes
        </VBtn>
      </VCardText>
    </VCard>
  </VDialog>
  <div class="d-flex justify-end">
    <VBtn color="primary" @click="handleCreate"> Add </VBtn>
  </div>
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
          {{ tableData.user.first_name }} {{ tableData.user.last_name }}
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
        <td>
        
          <div class="d-flex gap-2">
            <VBtn
              icon
              size="x-small"
              color="default"
              variant="text"
              @click="handleEdit(tableData)"
            >
              <VTooltip
                activator="parent"
                location="top"
                transition="scale-transition"
              >
                Delete
              </VTooltip>
              <VIcon size="22" icon="tabler-edit" />
            </VBtn>
          
            <VBtn
              icon
              size="x-small"
              color="warning"
              variant="text"
              @click="deletePromise(tableData.id)"
            >
              <VTooltip
                activator="parent"
                location="top"
                transition="scale-transition"
              >
                Delete
              </VTooltip>
              <VIcon size="22" icon="tabler-trash" />
            </VBtn>
          </div>
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
