import { useForm, router, usePage } from '@inertiajs/vue3';

import { debounce } from 'lodash';
import { ref, computed, onMounted } from 'vue';

export function useCrud({ routeName, routeIndex = null, redirect = null, perPage = 100, extraProps = {} }) {
  const paginatedData = computed(() => usePage().props.data);
  const serverParams = computed(() => usePage().props.params);
  const isLoadingComponents = ref(false);
  const offCanvas = ref(null);
  const isLoading = ref(false)

  const serverQuery = ref({
    perPage: perPage,
    query: null,
    page: 1,
    sort: 'id',
    order: 'desc',
    tab:null,
    ...extraProps,
  });

  onMounted(() => {
    if (serverParams.value) {
      if (serverParams.value.page) {
        serverQuery.value.page = serverParams.value.page;
      }
      if (serverParams.value.perPage) {
        serverQuery.value.perPage = serverParams.value.perPage;
      }
      if (serverParams.value.query) {
        serverQuery.value.query = serverParams.value.query;
      }
      if (serverParams.value.sort) {
        serverQuery.value.sort = serverParams.value.sort;
      }
      if (serverParams.value.order) {
        serverQuery.value.order = serverParams.value.order;
      }
      if (serverParams.value.role) {
        serverQuery.value.role = serverParams.value.role;
      }

      if (serverParams.value.coach_filter) {
        serverQuery.value.coach_filter = serverParams.value.coach_filter;
      }
      if (serverParams.value.service_filter) {
        serverQuery.value.service_filter = serverParams.value.service_filter;
      }
    }
  });

  const handleServerQuery = (key, value, sorting = true) => {
    if (!sorting){
      return;
    }
    if (key == 'perPage' || key == 'query') {
      serverQuery.value['page'] = 1;
    }
    if (key == 'sort') {
      if (!serverQuery.value.sort) {
        serverQuery.value['sort'] = value;
        serverQuery.value['order'] = 'asc';
      } else {
        if (serverQuery.value.sort == value) {
          if (serverQuery.value['order'] == 'asc') {
            serverQuery.value['sort'] = value;
            serverQuery.value['order'] = 'desc';
          } else {
            serverQuery.value['sort'] = null;
            serverQuery.value['order'] = null;
          }
        } else {
          serverQuery.value['sort'] = value;
          serverQuery.value['order'] = 'asc';
        }
      }
    } else {
      serverQuery.value[key] = value;
    }
    handleDebouncedServerQuery();
  };

  const handlePageChange = page => {
    serverQuery.value['page'] = page;
    handleDebouncedServerQuery();
  };

  const handleDebouncedServerQuery = debounce(() => {
    let routeValue = route(`${routeName}`, serverQuery.value);
    
    if (routeIndex) {
      routeValue = route(routeIndex.routeName, {
        id: routeIndex.routeId,
      });
    }

    let params = {};
    for (const key in serverQuery.value) {
      // eslint-disable-next-line sonarjs/no-collapsible-if
      if (serverQuery.value[key] && serverQuery.value[key] != '') {
        if (extraProps[key] == undefined){
          params[key] = serverQuery.value[key];
        }
      }
    }
   
    router.get(routeValue, params, {
      preserveState: true,
      preventScroll: true,
      only: ['data', 'params', 'status', 'success', 'error'],
      onSuccess: () => {
        isLoading.value = false;
      }
    });
  
  }, 500);

  const changeTab = (value) =>{
    
    isLoading.value = true;
    console.log(isLoading.value)
    serverQuery.value['tab'] = value;
    handleDebouncedServerQuery();
   
  }

  return {
    paginatedData,
    isLoading,
    isLoadingComponents,
    serverQuery,
    handleServerQuery,
    handlePageChange,
    changeTab,
  };
}
