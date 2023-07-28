<template>
  <q-page class="q-pa-md">
    <q-table title="Usuários" :rows="rows" :columns="columns" row-key="name" />
  </q-page>
</template>

<script>
import { defineComponent, onMounted, reactive } from 'vue';
import { api } from 'src/boot/axios';

const columns = [
  {
    name: 'name',
    required: true,
    label: 'Nome',
    align: 'center',
    sortable: true
  },
  { name: 'email', label: 'E-mail', sortable: true },
  { name: 'rg', label: 'RG', sortable: true },
  { name: 'cpf', label: 'CPF', sortable: true }
]

export default defineComponent({
  name: 'IndexPage',
  setup() {

    const rows = reactive([]);

    async function getUsers() {
      const { data } = await api.get('/users')
      rows = data.data;
    }

    onMounted(() => getUsers());

    return {
      columns,
      rows
    }
  }
})
</script>
