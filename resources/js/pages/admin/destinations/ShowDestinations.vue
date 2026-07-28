<script setup>

import axios from 'axios';
import { ref, onMounted, reactive, defineEmits, defineComponent,watch, computed } from "vue";
import moment from 'moment'
import {useToastr} from '../../../toastr';
import {debounce} from 'lodash';
import {Form, Field} from 'vee-validate';
import { useRouter} from "vue-router";
import * as yup from 'yup';
import VueFeather from 'vue-feather';
import { Bootstrap4Pagination } from 'laravel-vue-pagination';

let retrievedData =ref([]);
let loadingSubmit =ref([true]);
let loadingDiv =ref([true]);
const router = useRouter();
let self = this;
const searchQuery = ref(null);
const equipments = ref([]);
let typeequipments = ref();
const lastUpdate = ref(new Date());

// Computed properties para KPIs
const availabilityPercentage = computed(() => {
    if (!retrievedData.value.available_equipments || !retrievedData.value.unavailable_equipments) return 0;
    const total = retrievedData.value.available_equipments.length + retrievedData.value.unavailable_equipments.length;
    return total !== 0 ? Math.round((100 * retrievedData.value.available_equipments.length) / total) : 0;
});

const formattedLastUpdate = computed(() => {
    return moment(lastUpdate.value).format('DD/MM/YYYY HH:mm');
});

const totalEquipments = computed(() => {
    if (!retrievedData.value.available_equipments || !retrievedData.value.unavailable_equipments || !retrievedData.value.imobilized_equipments) return 0;
    return retrievedData.value.available_equipments.length + retrievedData.value.unavailable_equipments.length + retrievedData.value.imobilized_equipments.length;
});

const getData = async (page = 1) => {
  axios.get(`/destinations/+${router.currentRoute.value.params.id}?page=${page}`, 
  {
        params:{
          query: searchQuery.value
        }
      })
       .then((response)=>{
        loadingDiv.value=false;
        retrievedData.value = response.data.destination;
        equipments.value = response.data.equipments;
        typeequipments.value = response.data.typeequipments;
        lastUpdate.value = new Date();
       }).catch(()=>{
        loadingDiv.value=false;
       })
}

watch(searchQuery,debounce(()=>{
    getData();
},300));


onMounted(()=>{
  
  getData();
})
</script>

<template>
    <div v-if="!loadingDiv">

        <!-- Cabeçalho -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1">Destino de Aplicação</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="#" @click.prevent="$router.push('/admin/destinations')">Destinos</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ retrievedData.name }}</li>
                    </ol>
                </nav>
            </div>
            <div class="text-end">
                <small class="text-muted d-block mb-2">
                    <vue-feather type="clock" size="14"></vue-feather>
                    Atualizado em: {{ formattedLastUpdate }}
                </small>
                <a @click="$router.go(-1)" class="btn btn-primary">
                    <vue-feather type="arrow-left" size="16"></vue-feather>
                    Voltar
                </a>
            </div>
        </div>

        <!-- Painel de Identificação da Empresa -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0">
                            <vue-feather type="briefcase" size="18"></vue-feather>
                            Informações da Empresa - {{ retrievedData.name }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 col-lg-4 mb-3">
                                <div class="d-flex align-items-start">
                                    <vue-feather type="home" class="text-primary me-2" size="18"></vue-feather>
                                    <div>
                                        <small class="text-muted d-block">Empresa</small>
                                        <strong>{{ retrievedData.company_name }}</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4 mb-3">
                                <div class="d-flex align-items-start">
                                    <vue-feather type="map-pin" class="text-primary me-2" size="18"></vue-feather>
                                    <div>
                                        <small class="text-muted d-block">Endereço</small>
                                        <strong>{{ retrievedData.company_address }}</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4 mb-3">
                                <div class="d-flex align-items-start">
                                    <vue-feather type="map" class="text-primary me-2" size="18"></vue-feather>
                                    <div>
                                        <small class="text-muted d-block">Província</small>
                                        <strong>{{ retrievedData.province.name }}</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4 mb-3">
                                <div class="d-flex align-items-start">
                                    <vue-feather type="hash" class="text-primary me-2" size="18"></vue-feather>
                                    <div>
                                        <small class="text-muted d-block">NUIT</small>
                                        <strong>{{ retrievedData.company_nuit }}</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4 mb-3">
                                <div class="d-flex align-items-start">
                                    <vue-feather type="phone" class="text-primary me-2" size="18"></vue-feather>
                                    <div>
                                        <small class="text-muted d-block">Telefone</small>
                                        <strong>{{ retrievedData.company_mobile }}</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4 mb-3">
                                <div class="d-flex align-items-start">
                                    <vue-feather type="mail" class="text-primary me-2" size="18"></vue-feather>
                                    <div>
                                        <small class="text-muted d-block">Email</small>
                                        <strong>{{ retrievedData.company_email }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- KPIs de Disponibilidade e Equipamentos -->
        <div class="row mb-4">
            <div class="col-12">
                <h5 class="mb-3">
                    <vue-feather type="activity" size="20"></vue-feather>
                    Equipamentos/Ativos
                </h5>
            </div>
            
            <!-- Card Total de Equipamentos -->
            <div class="col-md-6 col-xl-3 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="row">
                            <div class="col mt-0">
                                <h5 class="card-title text-muted">Total de Equipamentos</h5>
                            </div>
                            <div class="col-auto">
                                <div class="stat stat-sm">
                                    <vue-feather type="package" class="text-primary" size="32"></vue-feather>
                                </div>
                            </div>
                        </div>
                        <h1 class="mt-1 mb-3 display-4 text-primary">{{ totalEquipments }}</h1>
                        <div class="mb-0">
                            <span class="badge bg-primary-light">Total no terminal</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card Disponibilidade Geral -->
            <div class="col-md-6 col-xl-3 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="row">
                            <div class="col mt-0">
                                <h5 class="card-title text-muted">Disponibilidade Geral</h5>
                            </div>
                            <div class="col-auto">
                                <div class="stat stat-sm">
                                    <vue-feather type="pie-chart" class="text-info" size="32"></vue-feather>
                                </div>
                            </div>
                        </div>
                        <h1 class="mt-1 mb-3 display-4" :class="availabilityPercentage >= 80 ? 'text-success' : availabilityPercentage >= 50 ? 'text-warning' : 'text-danger'">
                            {{ availabilityPercentage }}%
                        </h1>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar" 
                                 :class="availabilityPercentage >= 80 ? 'bg-success' : availabilityPercentage >= 50 ? 'bg-warning' : 'bg-danger'"
                                 role="progressbar" 
                                 :style="{ width: availabilityPercentage + '%' }"
                                 :aria-valuenow="availabilityPercentage" 
                                 aria-valuemin="0" 
                                 aria-valuemax="100">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card Disponíveis -->
            <div class="col-md-6 col-xl-3 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="row">
                            <div class="col mt-0">
                                <h5 class="card-title text-muted">Disponíveis</h5>
                            </div>
                            <div class="col-auto">
                                <div class="stat stat-sm">
                                    <vue-feather type="check-circle" class="text-success" size="32"></vue-feather>
                                </div>
                            </div>
                        </div>
                        <h1 class="mt-1 mb-3 display-4 text-success">{{ retrievedData.available_equipments.length }}</h1>
                        <div class="mb-0">
                            <span class="badge bg-success-light">Operacionais</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card Indisponíveis -->
            <div class="col-md-6 col-xl-3 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="row">
                            <div class="col mt-0">
                                <h5 class="card-title text-muted">Indisponíveis</h5>
                            </div>
                            <div class="col-auto">
                                <div class="stat stat-sm">
                                    <vue-feather type="alert-circle" class="text-warning" size="32"></vue-feather>
                                </div>
                            </div>
                        </div>
                        <h1 class="mt-1 mb-3 display-4 text-warning">{{ retrievedData.unavailable_equipments.length }}</h1>
                        <div class="mb-0">
                            <span class="badge bg-warning-light">Em manutenção</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card Não Operacionais -->
            <div class="col-md-6 col-xl-3 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="row">
                            <div class="col mt-0">
                                <h5 class="card-title text-muted">Não Operacionais</h5>
                            </div>
                            <div class="col-auto">
                                <div class="stat stat-sm">
                                    <vue-feather type="x-circle" class="text-danger" size="32"></vue-feather>
                                </div>
                            </div>
                        </div>
                        <h1 class="mt-1 mb-3 display-4 text-danger">{{ retrievedData.imobilized_equipments.length }}</h1>
                        <div class="mb-0">
                            <span class="badge bg-danger-light">Imobilizados</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- KPIs de Atividades -->
        <div class="row mb-4">
            <div class="col-12">
                <h5 class="mb-3">
                    <vue-feather type="calendar" size="20"></vue-feather>
                    Atividades
                </h5>
            </div>
            
            <!-- Card Atividades Planeadas -->
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="row">
                            <div class="col mt-0">
                                <h5 class="card-title text-muted">Atividades Planeadas</h5>
                            </div>
                            <div class="col-auto">
                                <div class="stat stat-sm">
                                    <vue-feather type="clipboard" class="text-primary" size="32"></vue-feather>
                                </div>
                            </div>
                        </div>
                        <h1 class="mt-1 mb-3 display-4 text-primary">{{ retrievedData.task_mcscr.length }}</h1>
                        <div class="mb-0">
                            <span class="text-muted">Manutenções programadas</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card Atividades Não Planeadas -->
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="row">
                            <div class="col mt-0">
                                <h5 class="card-title text-muted">Atividades Não Planeadas</h5>
                            </div>
                            <div class="col-auto">
                                <div class="stat stat-sm">
                                    <vue-feather type="alert-triangle" class="text-danger" size="32"></vue-feather>
                                </div>
                            </div>
                        </div>
                        <h1 class="mt-1 mb-3 display-4 text-danger">{{ retrievedData.mcscr.length }}</h1>
                        <div class="mb-0">
                            <span class="text-muted">Manutenções corretivas</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabela de Frotas -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <vue-feather type="truck" size="18"></vue-feather>
                            Frota por Tipo de Equipamento
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Frota</th>
                                        <th class="text-center">Equipamentos/Ativos</th>
                                        <th class="text-center">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(actualData,index) in typeequipments" :key="actualData.id">
                                        <td>
                                            <strong>{{ index }}</strong>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-primary">{{ actualData.length }}</span>
                                        </td>
                                        <td class="text-center">
                                            <router-link 
                                                :to="'/admin/destinations/'+retrievedData.id+'/fleet/'+actualData[0].type_equipment_id"
                                                class="btn btn-sm btn-outline-primary">
                                                <vue-feather type="eye" size="16"></vue-feather>
                                                Visualizar
                                            </router-link>                                                                         
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabela de Equipamentos -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">
                                <vue-feather type="settings" size="18"></vue-feather>
                                Lista de Equipamentos
                            </h5>
                            <div class="col-md-4">
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <vue-feather type="search" size="16"></vue-feather>
                                    </span>
                                    <input type="text" class="form-control" v-model="searchQuery" placeholder="Procurar equipamento...">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nome</th>
                                        <th>Ref</th>
                                        <th>Marca</th>
                                        <th>Tipo de Equipamento</th>
                                        <th>Área</th>
                                        <th>Destino</th>
                                        <th>Modelo</th>
                                        <th>Ano de Compra</th>
                                        <th>Estado</th>
                                        <th>Operação</th>
                                        <th class="text-center">Ações</th>
                                    </tr>
                                </thead>
                                <tbody v-if="equipments.data && equipments.data.length > 0">
                                    <tr v-for="(actualData,index) in equipments.data" :key="actualData.id">
                                        <td>#{{ index + 1 }}</td>
                                        <td><strong>{{ actualData.name }}</strong></td>
                                        <td>{{ actualData.ref }}</td>
                                        <td>{{ actualData.make }}</td>
                                        <td>{{ actualData.type_equipment.name }}</td>
                                        <td>{{ actualData.area.name }}</td>
                                        <td>{{ actualData.destination.name }}</td>
                                        <td>{{ actualData.model }}</td>
                                        <td>{{ actualData.buy_year }}</td>
                                        <td>
                                            <span class="badge bg-success" v-if="actualData.equipment_status.id == 1">
                                                {{ actualData.equipment_status.name }}
                                            </span> 
                                            <span class="badge bg-danger" v-if="actualData.equipment_status.id == 2">
                                                {{ actualData.equipment_status.name }}
                                            </span>
                                            <span class="badge bg-danger" v-if="actualData.equipment_status.id == 3">
                                                {{ actualData.equipment_status.name }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-success" v-if="actualData.equipment_status.id == 1">
                                                {{ actualData.equipment_status.mobilized }}
                                            </span> 
                                            <span class="badge bg-success" v-if="actualData.equipment_status.id == 2">
                                                {{ actualData.equipment_status.mobilized }}
                                            </span>
                                            <span class="badge bg-danger" v-if="actualData.equipment_status.id == 3">
                                                {{ actualData.equipment_status.mobilized }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <router-link 
                                                    :to="'/admin/equipments/'+actualData.id+'/edit'" 
                                                    class="btn btn-sm btn-outline-primary"
                                                    title="Editar">
                                                    <vue-feather type="edit-2" size="14"></vue-feather>
                                                </router-link>
                                                <router-link 
                                                    :to="'/admin/equipments/'+actualData.id" 
                                                    class="btn btn-sm btn-outline-info"
                                                    title="Visualizar">
                                                    <vue-feather type="eye" size="14"></vue-feather>
                                                </router-link> 
                                                <router-link 
                                                    :to="'/admin/equipments/'+actualData.id+'/file'" 
                                                    class="btn btn-sm btn-outline-secondary"
                                                    title="Arquivos">
                                                    <vue-feather type="file" size="14"></vue-feather>
                                                </router-link>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                                <tbody v-else>
                                    <tr>
                                        <td colspan="12" class="text-center py-4">
                                            <vue-feather type="inbox" size="48" class="text-muted mb-2"></vue-feather>
                                            <p class="text-muted">Nenhum resultado encontrado</p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            <Bootstrap4Pagination :data="equipments" @pagination-change-page="getData"/>
                        </div>
                    </div>
                </div>
            </div>   
        </div>
    </div>
    <div v-else>
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-center">
                    <div class="spinner-border" role="status">
                        <span class="sr-only"></span>
                    </div>
                </div>
                <br>
                <div class="d-flex justify-content-center">
                    Carregando Dados...
                </div>
            </div> 
        </div>
    </div>
</template>