<template>
  <q-page class="q-pa-lg">
    <div class="row q-gutter-md items-center q-pa-md">
      <div class="col">
        <q-input outlined v-model="search" label="Busca por nome" clearable clear-icon="close" />
      </div>
      <div class="col">
        <q-btn color="primary" icon="plus" label="Adicionar" @click="handleDialog(null)" />
      </div>
    </div>

    <q-table title="Usuários" :rows="rows" :columns="columns" row-key="id" :loading="loading"
      :pagination="initialPagination" rows-per-page-label="Registros por página"
      no-data-label="Nenhum registro encontrado">

      <template v-slot:pagination>

        <q-pagination v-model="currentPage" :max="data.meta.last_page" direction-links flat color="indigo-9"
          :max-pages="6" active-color="primary" boundary-links>
        </q-pagination>

      </template>

      <template v-slot:body="props">
        <q-tr :props="props">
          <q-td key="name" :props="props">
            {{ props.row.name }}
          </q-td>
          <q-td key="email" :props="props">
            {{ props.row.email }}
          </q-td>
          <q-td key="rg" :props="props">
            {{ props.row.people.rg }}
          </q-td>
          <q-td key="cpf" :props="props">
            {{ props.row.people.cpf }}
          </q-td>
          <q-td key="action" :props="props">

            <div class="q-pa-md q-gutter-sm row items-center justify-center">
              <q-btn round color="primary" icon="edit" size="small" @click="handleDialog(props.row, 'open')" />

              <q-btn round color="negative" icon="delete" size="small" @click="remove(props.row.id)" />
            </div>

          </q-td>
        </q-tr>
      </template>

    </q-table>

    <q-dialog v-model="dialog">
      <q-card style="width: 800px; max-width: 80vw;">
        <q-card-section>
          <div class="text-h6">{{ row.name }}</div>
        </q-card-section>

        <q-card-section v-if="errors">
          <q-list dense bordered padding class="rounded-borders">
            <q-item clickable v-ripple v-for="(error, index) in errors" :key="index">
              <q-item-section>
                {{ error[0] }}
              </q-item-section>
            </q-item>
          </q-list>
        </q-card-section>

        <q-card-section class="q-pt-none">

          <form-user v-model:form="row" />
        </q-card-section>

        <q-card-actions align="right" class="bg-white text-teal">
          <q-btn flat label="Cancelar" v-close-popup />
          <q-btn flat label="Salvar" v-close-popup @click="row.id ? update(row) : create()" />
        </q-card-actions>
      </q-card>
    </q-dialog>
  </q-page>
</template>

<script setup>
import { onMounted, reactive, ref, watch } from 'vue';
import { api } from 'src/boot/axios';
import { useQuasar, Notify } from 'quasar'
import FormUser from './FormUser.vue'

const columns = [
  {
    name: 'name',
    required: true,
    label: 'Nome',
    field: 'name',
    align: 'start',
    sortable: true
  },
  { name: 'email', field: 'email', label: 'E-mail', align: 'center', sortable: true },
  { name: 'rg', field: row => row.people.rg, label: 'RG', align: 'center', sortable: true },
  { name: 'cpf', field: row => row.people.cpf, label: 'CPF', align: 'center', sortable: true },
  { name: 'action', label: 'Ações', align: 'center', sortable: false }
]

const initialPagination = reactive({
  sortBy: 'desc',
  descending: false,
  page: 1,
  rowsPerPage: 10
});

const rows = ref([]);
const row = ref({});
const errors = ref(null);
const data = ref({});
const currentPage = ref(1);
const loading = ref(false);
const dialog = ref(false);
const search = ref('');
const $q = useQuasar();

const setRows = (users) => rows.value = users;
const setData = (users) => data.value = users;
const handleLoading = () => loading.value = !loading.value;

async function getUsers(filter = { page: currentPage.value }) {
  handleLoading();

  let filters = '';

  for (const key in filter) {
    filters += `${key}=${filter[key]}`;
  }

  const { data } = await api.get(`/users?${filters}`);

  if (data.data.length > 0) {

    setData(data);
    setRows(data.data);
  }

  handleLoading();
}

function remove(userId) {
  $q.dialog({
    title: 'Confirmação de exclusão',
    message: 'Tem certeza disso ?',
    cancel: true,
    persistent: true
  }).onOk(async () => {

    handleLoading();
    try {
      const resp = await api.delete(`users/${userId}`);

      if (resp.status === 200) {
        rows.value = rows.value.filter(row => row.id !== userId);

        notifySuccess("Usuário removido.");
      }
    } finally {
      handleLoading();
    }

  });
}

function handleDialog(item) {
  dialog.value = true;

  if (item) {
    row.value = item;
  } else {
    row.value = {
      name: 'Danilo',
      email: 'danilovsdanilo@gmail.com',
      password: 'danilo123',
      people: {
        name: 'Danilo Veloso',
        rg: '258477',
        cpf: '52930157291',
        birthday: '1991-08-30',
        address: [
          {
            cep: '69317-466',
            country: 'Brasil',
            state: 'Roraima',
            neighborhoods: 'Av universo',
            street: 'Cidade Satélite',
            complement: 'complemenot'
          }
        ],
      }
    };
  }
}

function notifyFailure(error) {

  Notify.create({ message: error?.response?.data?.message, type: 'negative' });
  errors.value = error?.response?.data?.errors;
}

function notifySuccess(message) {

  Notify.create({ message, type: 'positive' });
  errors.value = null;
}

function create() {
  api.post('users/', row.value)
    .then(async () => {
      notifySuccess("Usuário criado.");
      await getUsers({ name: row.value.name });
    })
    .catch(error => notifyFailure(error));
}

function update(item) {

  if (item.id) {

    api.put(`users/${item.id}`, item)
      .then(() => notifySuccess("Usuário atualizado."))
      .catch(error => notifyFailure(error));
  }
}

onMounted(() => getUsers());

watch(currentPage, async (value) => await getUsers({ page: value }));
watch(search, async (value) => {

  if (value === null) {
    value = "";
  }

  await getUsers({ name: value?.toLocaleLowerCase() })
});

</script>
