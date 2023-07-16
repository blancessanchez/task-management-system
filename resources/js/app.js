require('./bootstrap');

import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap';

import Vue from 'vue';
import KanbanBoard from './components/KanbanBoard.vue';

Vue.component('kanban-board', KanbanBoard);

const app = new Vue({
    el: '#app',
});
