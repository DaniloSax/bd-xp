<template>
  <div class="q-pa-md column q-gutter-sm">

    <div class="text-subtitle1">Dados do Usuário</div>
    <q-separator />
    <div class="row q-gutter-sm">

      <div class="col-md-3 col-sm-12">
        <q-input outlined v-model="form.name" label="Nome" />
      </div>

      <div class="col-md-3 col-sm-12">
        <q-input outlined v-model="form.email" label="E-mail" />
      </div>

      <div class="col-md-3 col-sm-12">
        <q-input outlined v-model="form.password" label="Senha" type="password" />
      </div>

    </div>

    <div class="text-subtitle1">Dados Pessoais</div>
    <q-separator />

    <div class="row q-gutter-sm">

      <div class="col-md-3 col-sm-12">
        <q-input outlined v-model="form.people.name" label="Nome completo" />
      </div>

      <div class="col-md-3 col-sm-12">
        <q-input outlined v-model="form.people.rg" label="RG" />
      </div>

      <div class="col-md-3 col-sm-12 ">
        <q-input outlined v-model="form.people.cpf" label="CPF" />
      </div>

      <div class="col-md-3 col-sm-12 ">
        <q-input outlined v-model="form.people.birthday" label="Data de Nascimento" type="date" />
      </div>

    </div>

    <div class="text-subtitle1">Endereços</div>
    <q-separator />

    <template v-for="address in form.people.address" :key="address.id">
      <div class="row q-gutter-sm">

        <div class="col-md-3 col-sm-12">
          <q-input outlined v-model="address.cep" label="CEP" @blur="handleAddress(address)" />
        </div>

        <div class="col-md-3 col-sm-12">
          <q-input outlined v-model="address.country" label="País" />
        </div>

        <div class="col-md-3 col-sm-12">
          <q-input outlined v-model="address.state" label="Estado" />
        </div>
      </div>

      <div class="row q-gutter-sm">

        <div class="col-md-3 col-sm-12">
          <q-input outlined v-model="address.neighborhoods" label="Bairro" />
        </div>

        <div class="col-md-3 col-sm-12">
          <q-input outlined v-model="address.street" label="Rua" />
        </div>

        <div class="col-md-3 col-sm-12">
          <q-input outlined v-model="address.number" label="Número" />
        </div>

        <div class="col-md-3 col-sm-12">
          <q-input outlined v-model="address.complement" label="Complemento" />
        </div>

      </div>
    </template>
  </div>
</template>

<script setup>
import { defineProps, ref, defineEmits, watch } from 'vue';
import { Notify } from 'quasar';
import axios from 'axios';

const props = defineProps({
  form: Object
});

const form = ref(props.form ?? {});

const emit = defineEmits(['update:form']);

watch(form.value, (value) => emit('update:form', value), { immediate: true, deep: true });

async function handleAddress(address) {
  const cep = address.cep.replace(/[^\d]/g, '');

  const baseURL = `https://viacep.com.br/ws/${cep}/json/`;

  try {
    const { data } = await axios.get(baseURL);

    let addr = {}

    if (form.value.people.address[0].id) {

      addr = form.value.people.address.map(addr => {

        if (addr.id === address.id && data) {
          return {
            ...addr,
            country: 'Brasil',
            state: data.uf,
            neighborhoods: data.bairro,
            street: data.logradouro,
            complement: data.complemento
          };
        }

        return addr;
      });

    } else {
      addr = {
        cep: data.cep,
        country: 'Brasil',
        state: data.uf,
        neighborhoods: data.bairro,
        street: data.logradouro,
        complement: data.complemento
      }
    }

    form.value.people.address[0] = addr;

    Notify.create({ message: 'CEP válido', color: 'positive', icon: 'done' });

  } catch (error) {
    Notify.create({ message: 'CEP inválido', color: 'negative', icon: 'error' });
  }

}

</script>
