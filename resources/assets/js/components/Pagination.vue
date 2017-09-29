<template>
    <nav>
        <ul class="pagination">
            <li class="page-item" v-if="pagination.current_page > 1">
                <a class="page-link" href="javascript:;" @click="callFetch(pagination.current_page - 1)">Prev</a>
            </li>
            <li v-for="(page, index) in pagination.last_page" :key="index" v-bind:class="[ page == pagination.current_page ? 'active' : '']">
                <a href="javascript:;" @click="callFetch(page)" v-if="index < pagination.current_page + 5 && index > pagination.current_page - 5">{{ page }}</a>
            </li>
            <li class="page-item" v-if="pagination.current_page <  pagination.last_page">
                <a class="page-link" href="javascript:;" @click="callFetch(pagination.current_page + 1)">Next</a>
            </li>
        </ul>
    </nav>

</template> 

<script>
export default {
    props: {
        pagination:{
            type: Object,
            required: true
        },
        fetchFunc: {
            type: Function,
            required: true
        },
    },
    methods: {
        callFetch(page) {
            this.fetchFunc(page);
        },
    }
}
</script>