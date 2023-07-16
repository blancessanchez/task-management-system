import Vue from 'vue';
import KanbanBoard from './components/KanbanBoard.vue';

Vue.component('kanban-board', KanbanBoard);

const app = new Vue({
    el: '#app',
});

$('.dropdown').click(function() {
    $('.dropdown-menu').toggleClass('show');
});