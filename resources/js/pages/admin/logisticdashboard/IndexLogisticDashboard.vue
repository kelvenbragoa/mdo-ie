<script setup>

import {onMounted, ref, reactive, computed, watch} from 'vue';
import axios from 'axios';
import VueFeather from 'vue-feather';
import moment from 'moment';
import { Bar, Pie, Line, Doughnut } from 'vue-chartjs';
import { 
    Chart as ChartJS, 
    Title, 
    Tooltip, 
    Legend, 
    BarElement, 
    CategoryScale, 
    LinearScale,
    ArcElement, 
    LineElement, 
    PointElement
} from 'chart.js';

ChartJS.register(
    Title, 
    Tooltip, 
    Legend, 
    BarElement, 
    CategoryScale, 
    LinearScale, 
    ArcElement, 
    LineElement, 
    PointElement
);


// Estado da aplicação
const loadingDiv = ref(true);
const activeTab = ref('daily'); // daily, weekly, monthly
const selectedPeriod = ref('today');
const selectedVehicle = ref('all');
const selectedDriver = ref('all');
const selectedRoute = ref('all');

// Dados do dashboard
const dashboardData = ref({
    kpis: {
        activeVehicles: 0,
        criticalAlerts: 0,
        totalCost: 0,
        efficiency: 0
    },
    daily: {
        speedViolations: 0,
        unscheduledStops: 0,
        offHourIgnitions: 0,
        gpsDisconnections: 0,
        fuelDrainage: 0,
        operationTime: 0,
        idleTime: 0,
        routeDeviations: 0
    },
    weekly: {
        driverRanking: [],
        fuelAnalysis: [],
        idleAnalysis: [],
        maintenance: [],
        routeCompliance: 0
    },
    monthly: {
        roi: 0,
        performanceTrend: [],
        costPerVehicle: [],
        securityReport: [],
        nextMonthGoals: []
    },
    vehicles: [],
    drivers: [],
    routes: []
});

// Configurações dos filtros
const periodOptions = [
    { value: 'today', label: 'Hoje' },
    { value: 'yesterday', label: 'Ontem' },
    { value: 'last7days', label: 'Últimos 7 dias' },
    { value: 'last30days', label: 'Últimos 30 dias' },
    { value: 'thisMonth', label: 'Este mês' },
    { value: 'lastMonth', label: 'Mês passado' },
    { value: 'thisYear', label: 'Este ano' }
];

// Dados dos gráficos
const chartData = reactive({
    dailyEfficiency: {
        labels: ['00:00', '04:00', '08:00', '12:00', '16:00', '20:00'],
        datasets: [{
            label: 'Tempo Operacional (h)',
            backgroundColor: '#3b82f6',
            data: [2, 4, 8, 6, 7, 3]
        }, {
            label: 'Tempo Ocioso (h)',
            backgroundColor: '#ef4444',
            data: [1, 2, 1, 3, 2, 4]
        }]
    },
    weeklyFuel: {
        labels: ['Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb', 'Dom'],
        datasets: [{
            label: 'Consumo Esperado (L)',
            backgroundColor: '#10b981',
            data: [120, 130, 125, 140, 135, 90, 80]
        }, {
            label: 'Consumo Real (L)',
            backgroundColor: '#f59e0b',
            data: [135, 145, 140, 160, 155, 110, 95]
        }]
    },
    monthlyTrend: {
        labels: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun'],
        datasets: [{
            label: 'Eficiência (%)',
            borderColor: '#3b82f6',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
            data: [85, 87, 84, 89, 91, 88],
            fill: true
        }, {
            label: 'Consumo Combustível (L/100km)',
            borderColor: '#ef4444',
            backgroundColor: 'rgba(239, 68, 68, 0.1)',
            data: [25, 24, 26, 23, 22, 24],
            fill: true
        }]
    },
    driverRanking: {
        labels: ['João Silva', 'Maria Santos', 'Pedro Costa', 'Ana Oliveira', 'Carlos Lima'],
        datasets: [{
            label: 'Pontuação de Eficiência',
            backgroundColor: ['#10b981', '#3b82f6', '#f59e0b', '#ef4444', '#8b5cf6'],
            data: [95, 88, 82, 78, 75]
        }]
    },
    routeCompliance: {
        labels: ['Cumpridas', 'Desvios Menores', 'Desvios Críticos'],
        datasets: [{
            backgroundColor: ['#10b981', '#f59e0b', '#ef4444'],
            data: [75, 20, 5]
        }]
    }
});

const chartOptions = {
    responsive: true,
    maintainAspectRatio: false
};

// Métodos
const fetchDashboardData = async () => {
    try {
        loadingDiv.value = true;
        
        // Simular dados - substitua pela sua API real
        const response = await axios.get('/api/logistics-dashboard', {
            params: {
                period: selectedPeriod.value,
                vehicle: selectedVehicle.value,
                driver: selectedDriver.value,
                route: selectedRoute.value
            }
        });
        
        // Se não houver API, usar dados simulados
        dashboardData.value = {
            kpis: {
                activeVehicles: 45,
                criticalAlerts: 3,
                totalCost: 125430,
                efficiency: 87.5
            },
            daily: {
                speedViolations: 12,
                unscheduledStops: 8,
                offHourIgnitions: 2,
                gpsDisconnections: 1,
                fuelDrainage: 0,
                operationTime: 142,
                idleTime: 28,
                routeDeviations: 5
            },
            weekly: {
                driverRanking: [
                    { name: 'João Silva', efficiency: 95, fuelScore: 92 },
                    { name: 'Maria Santos', efficiency: 88, fuelScore: 89 },
                    { name: 'Pedro Costa', efficiency: 82, fuelScore: 85 }
                ],
                fuelAnalysis: [],
                idleAnalysis: [],
                maintenance: [
                    { vehicle: 'Caminhão 001', daysToMaintenance: 5, type: 'Preventiva' },
                    { vehicle: 'Van 003', daysToMaintenance: 12, type: 'Revisão' }
                ],
                routeCompliance: 85.2
            },
            monthly: {
                roi: 15.3,
                performanceTrend: [],
                costPerVehicle: [],
                securityReport: {
                    incidents: 2,
                    fines: 1,
                    riskBehavior: 8
                },
                nextMonthGoals: []
            },
            vehicles: [
                { id: 1, name: 'Caminhão 001' },
                { id: 2, name: 'Van 002' },
                { id: 3, name: 'Ônibus 003' }
            ],
            drivers: [
                { id: 1, name: 'João Silva' },
                { id: 2, name: 'Maria Santos' }
            ],
            routes: [
                { id: 1, name: 'Rota Centro' },
                { id: 2, name: 'Rota Industrial' }
            ]
        };
        
    } catch (error) {
        console.error('Erro ao carregar dados:', error);
    } finally {
        loadingDiv.value = false;
    }
};

const exportToPDF = () => {
    window.print();
};

const exportToExcel = () => {
    // Implementar exportação para Excel
    console.log('Exportando para Excel...');
};

const changeTab = (tab) => {
    activeTab.value = tab;
};

// Watchers
watch([selectedPeriod, selectedVehicle, selectedDriver, selectedRoute], () => {
    fetchDashboardData();
});

onMounted(() => {
    fetchDashboardData();
});



</script>

<template>
    <div v-if="!loadingDiv" class="fleet-dashboard">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Dashboard de Monitoramento de Frota</h1>
            <div class="d-flex gap-2">
                <button @click="exportToPDF" class="btn btn-outline-primary btn-sm">
                    <vue-feather type="download" size="16"></vue-feather>
                    PDF
                </button>
                <button @click="exportToExcel" class="btn btn-outline-success btn-sm">
                    <vue-feather type="file-text" size="16"></vue-feather>
                    Excel
                </button>
            </div>
        </div>

        <!-- Filtros -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Período</label>
                        <select v-model="selectedPeriod" class="form-select">
                            <option v-for="option in periodOptions" :key="option.value" :value="option.value">
                                {{ option.label }}
                            </option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Veículo</label>
                        <select v-model="selectedVehicle" class="form-select">
                            <option value="all">Todos os veículos</option>
                            <option v-for="vehicle in dashboardData.vehicles" :key="vehicle.id" :value="vehicle.id">
                                {{ vehicle.name }}
                            </option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Motorista</label>
                        <select v-model="selectedDriver" class="form-select">
                            <option value="all">Todos os motoristas</option>
                            <option v-for="driver in dashboardData.drivers" :key="driver.id" :value="driver.id">
                                {{ driver.name }}
                            </option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Rota</label>
                        <select v-model="selectedRoute" class="form-select">
                            <option value="all">Todas as rotas</option>
                            <option v-for="route in dashboardData.routes" :key="route.id" :value="route.id">
                                {{ route.name }}
                            </option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- KPIs Principais -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="card text-white bg-primary">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-1">{{ dashboardData.kpis.activeVehicles }}</h4>
                                <p class="mb-0">Veículos Ativos</p>
                            </div>
                            <vue-feather type="truck" size="24"></vue-feather>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card text-white bg-danger">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-1">{{ dashboardData.kpis.criticalAlerts }}</h4>
                                <p class="mb-0">Alertas Críticos</p>
                            </div>
                            <vue-feather type="alert-triangle" size="24"></vue-feather>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card text-white bg-success">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-1">{{ dashboardData.kpis.efficiency }}%</h4>
                                <p class="mb-0">Eficiência Média</p>
                            </div>
                            <vue-feather type="trending-up" size="24"></vue-feather>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card text-white bg-warning">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-1">R$ {{ dashboardData.kpis.totalCost.toLocaleString() }}</h4>
                                <p class="mb-0">Custo Total</p>
                            </div>
                            <vue-feather type="dollar-sign" size="24"></vue-feather>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navegação de Relatórios -->
        <div class="card mb-4">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs" role="tablist">
                    <li class="nav-item">
                        <button 
                            class="nav-link" 
                            :class="{ active: activeTab === 'daily' }"
                            @click="changeTab('daily')"
                        >
                            <vue-feather type="calendar" size="16" class="me-1"></vue-feather>
                            Relatório Diário
                        </button>
                    </li>
                    <li class="nav-item">
                        <button 
                            class="nav-link" 
                            :class="{ active: activeTab === 'weekly' }"
                            @click="changeTab('weekly')"
                        >
                            <vue-feather type="bar-chart-2" size="16" class="me-1"></vue-feather>
                            Relatório Semanal
                        </button>
                    </li>
                    <li class="nav-item">
                        <button 
                            class="nav-link" 
                            :class="{ active: activeTab === 'monthly' }"
                            @click="changeTab('monthly')"
                        >
                            <vue-feather type="trending-up" size="16" class="me-1"></vue-feather>
                            Relatório Mensal
                        </button>
                    </li>
                </ul>
            </div>

            <!-- Relatório Diário -->
            <div v-if="activeTab === 'daily'" class="card-body">
                <h5 class="card-title">Relatório Diário - Foco Operacional</h5>
                
                <!-- Resumo de Ocorrências -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card border-left-warning">
                            <div class="card-body">
                                <h6 class="text-warning">Resumo de Ocorrências</h6>
                                <ul class="list-unstyled mb-0">
                                    <li><strong>{{ dashboardData.daily.speedViolations }}</strong> Excesso de velocidade</li>
                                    <li><strong>{{ dashboardData.daily.unscheduledStops }}</strong> Paradas não programadas</li>
                                    <li><strong>{{ dashboardData.daily.offHourIgnitions }}</strong> Ignições fora de horário</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-left-danger">
                            <div class="card-body">
                                <h6 class="text-danger">Alertas Críticos</h6>
                                <ul class="list-unstyled mb-0">
                                    <li><strong>{{ dashboardData.daily.gpsDisconnections }}</strong> Tentativas desconexão GPS</li>
                                    <li><strong>{{ dashboardData.daily.fuelDrainage }}</strong> Drenagem combustível</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-left-info">
                            <div class="card-body">
                                <h6 class="text-info">Eficiência do Dia</h6>
                                <ul class="list-unstyled mb-0">
                                    <li><strong>{{ dashboardData.daily.operationTime }}h</strong> Tempo operação</li>
                                    <li><strong>{{ dashboardData.daily.idleTime }}h</strong> Tempo ociosidade</li>
                                    <li><strong>{{ dashboardData.daily.routeDeviations }}</strong> Desvios de rota</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Gráfico de Eficiência Diária -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Eficiência Operacional vs Ociosidade (Últimas 24h)</h6>
                            </div>
                            <div class="card-body">
                                <div style="height: 300px;">
                                    <Bar :data="chartData.dailyEfficiency" :options="chartOptions" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Relatório Semanal -->
            <div v-if="activeTab === 'weekly'" class="card-body">
                <h5 class="card-title">Relatório Semanal - Foco Tático</h5>
                
                <div class="row mb-4">
                    <!-- Ranking de Motoristas -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Ranking de Motoristas</h6>
                            </div>
                            <div class="card-body">
                                <div style="height: 300px;">
                                    <Bar :data="chartData.driverRanking" :options="chartOptions" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Análise de Combustível -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Consumo de Combustível Semanal</h6>
                            </div>
                            <div class="card-body">
                                <div style="height: 300px;">
                                    <Bar :data="chartData.weeklyFuel" :options="chartOptions" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <!-- Cumprimento de Rotas -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Cumprimento de Rotas</h6>
                            </div>
                            <div class="card-body">
                                <div style="height: 300px;">
                                    <Pie :data="chartData.routeCompliance" :options="chartOptions" />
                                </div>
                                <div class="text-center mt-2">
                                    <span class="badge bg-success">{{ dashboardData.weekly.routeCompliance }}% de aderência</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Manutenção Preventiva -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Manutenção Preventiva</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Veículo</th>
                                                <th>Dias Restantes</th>
                                                <th>Tipo</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="maintenance in dashboardData.weekly.maintenance" :key="maintenance.vehicle">
                                                <td>{{ maintenance.vehicle }}</td>
                                                <td>
                                                    <span 
                                                        class="badge" 
                                                        :class="maintenance.daysToMaintenance <= 7 ? 'bg-danger' : 'bg-warning'"
                                                    >
                                                        {{ maintenance.daysToMaintenance }} dias
                                                    </span>
                                                </td>
                                                <td>{{ maintenance.type }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Relatório Mensal -->
            <div v-if="activeTab === 'monthly'" class="card-body">
                <h5 class="card-title">Relatório Mensal - Foco Estratégico</h5>
                
                <div class="row mb-4">
                    <!-- ROI e Tendência -->
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Tendência de Performance</h6>
                            </div>
                            <div class="card-body">
                                <div style="height: 300px;">
                                    <Line :data="chartData.monthlyTrend" :options="chartOptions" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Métricas ROI -->
                    <div class="col-md-4">
                        <div class="card h-100">
                            <div class="card-header">
                                <h6 class="mb-0">ROI do Sistema</h6>
                            </div>
                            <div class="card-body d-flex flex-column justify-content-center">
                                <div class="text-center">
                                    <h2 class="text-success">{{ dashboardData.monthly.roi }}%</h2>
                                    <p class="text-muted">Retorno sobre investimento</p>
                                </div>
                                
                                <hr>
                                
                                <div class="mb-3">
                                    <h6>Relatório de Segurança</h6>
                                    <ul class="list-unstyled">
                                        <li><strong>{{ dashboardData.monthly.securityReport.incidents }}</strong> Incidentes</li>
                                        <li><strong>{{ dashboardData.monthly.securityReport.fines }}</strong> Multas</li>
                                        <li><strong>{{ dashboardData.monthly.securityReport.riskBehavior }}</strong> Comportamentos de risco</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Metas do Próximo Mês -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Metas do Próximo Mês</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="text-center p-3 border rounded">
                                            <h5 class="text-primary">-5%</h5>
                                            <small>Redução consumo combustível</small>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="text-center p-3 border rounded">
                                            <h5 class="text-success">+10%</h5>
                                            <small>Aumento eficiência operacional</small>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="text-center p-3 border rounded">
                                            <h5 class="text-warning">90%</h5>
                                            <small>Aderência às rotas</small>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="text-center p-3 border rounded">
                                            <h5 class="text-info">Zero</h5>
                                            <small>Incidentes de segurança</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading State -->
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
                    Carregando dashboard...
                </div>
            </div> 
        </div>
    </div>
</template>

<style scoped>
.fleet-dashboard {
    padding: 20px;
}

.border-left-warning {
    border-left: 4px solid #f59e0b !important;
}

.border-left-danger {
    border-left: 4px solid #ef4444 !important;
}

.border-left-info {
    border-left: 4px solid #3b82f6 !important;
}

.nav-tabs .nav-link {
    border: none;
    color: #6c757d;
}

.nav-tabs .nav-link.active {
    background-color: #fff;
    border-bottom: 2px solid #0d6efd;
    color: #0d6efd;
}

.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: 1px solid rgba(0, 0, 0, 0.125);
}

.bg-primary {
    background-color: #0d6efd !important;
}

.bg-danger {
    background-color: #dc3545 !important;
}

.bg-success {
    background-color: #198754 !important;
}

.bg-warning {
    background-color: #ffc107 !important;
    color: #000 !important;
}

.gap-2 {
    gap: 0.5rem;
}

.me-1 {
    margin-right: 0.25rem;
}

.g-3 > * {
    padding: 0.75rem;
}
</style>