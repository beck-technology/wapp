<template>
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-sm-12">
                <div class="card border-0">
                    <!-- <div class="card-header">
                        Companies
                    </div> -->
                    <div class="card-block">
                        <div class="table-container">
                            <div class="table-container">
                                <datatable v-bind="tblData1">
                                    <table-navigation :selection='tblData1.selection' :query='tblData1.query' :createRoute="'Add Company'" :editRoute="'Edit Company'" :tableClass="'tblData1'" :deleteUrl="'/api/company/deletetin'">
                                    </table-navigation>
                                </datatable>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import Vue from 'vue'
    import Datatable from 'vue2-datatable-component'
    import components from '../comps'
    import TableNavigation from '../partials/TableNavigation'
     Vue.use(Datatable);
    export default {
        components: {
            ...components,
            TableNavigation
        },
        name: 'CompanyList', // `name` is required as a recursive component
            //props: ['row'], // from the parent FriendsTable (if exists)
        data:() => ({
            locations: [],
            tblData1: {
                tblClass: 'table-responsive table-bordered tblData1',
                tblStyle: 'color: #666',
                tblId: 'dataTable1',
                pageSizeOptions: [5, 10, 15, 20],
                columns: (() => {
                    const cols = [
                        // { title: 'Account Name', field: 'account_name', thComp: 'FilterTh', sortable: false, tdStyle: { fontStyle: 'italic' } },
                        { title: 'Company Name', field: 'name', thComp: 'FilterTh', sortable: true, tdStyle: { fontStyle: 'italic' }, tdComp: 'showCompanyEditLinkTd' },
                        { title: 'Company Logo', field: 'image', visible: true, sortable: false, thComp: '', tdComp: 'ShowImageTd' },
                        { title: 'Website', field: 'website', visible: true, sortable: true, thComp: 'FilterTh', tdComp: 'ShowLinkWebsiteTd' },
                        { title: 'Address', field: 'address', visible: true, sortable: true, thComp: 'FilterTh' },
                        { title: 'Post Code', field: 'post_code', visible: true, sortable: true, thComp: 'FilterTh' },
                        { title: 'Company Type', field: 'type', visible: true, sortable: true, thComp: 'FilterTh' },
                        { title: 'Phone Number', field: 'phone_number', visible: true, sortable: true, thComp: 'FilterTh' },
                        { title: 'Parent company', field: 'is_parent', visible: true, sortable: true, thComp: '', tdComp: 'ShowBooleanValueTd' },
                        { title: 'Enable', field: 'is_enable', visible: true, sortable: true, thComp: '', tdComp: 'ShowBooleanValueTd' }
                    ]
                    const groupsDef = {
                        Normal: ['email', 'name', 'website', 'address', 'post_code', 'phone_number'],
                        Sortable: [],
                        Extra: []
                    }
                    return cols.map(col => {
                            Object.keys(groupsDef).forEach(groupName => {
                                if (groupsDef[groupName].includes(col.title)) {
                                    col.group = groupName
                                }
                            })
                        return col
                    })
                })(),
                data: [],
                total: 0,
                selection: [],
                query: {},
                xprops: {
                    eventbus: new Vue()
                }
            },
            tblData2: {}
        }),
        watch: {
            'tblData1.query': {
                handler () {
                    this.handleQueryChange();
                },
                deep: true
            }
        },
        created() {
            var self = this;
            setTimeout(function() {
                self.handleQueryChange();
            }, 1000);
        },
        methods: {
            handleQueryChange () {
                this.$http.get('/api/companies', {
                            params:  {...this.tblData1.query },
                            headers: {
							Authorization: `Bearer ${this.$store.state.token}`
						}
                           
                })
                .then(response => {
                    this.tblData1.data = response.data.companies;
                    this.tblData1.total = response.data.total;
                    
                });
             },
        }
    }
</script>
 <style>
    .w-240 {
        width: 240px;
    }
</style>