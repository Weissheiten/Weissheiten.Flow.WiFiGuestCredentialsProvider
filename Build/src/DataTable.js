import React, { Component } from 'react';
import DataTableGroup from './DataTableGroup'
import DataTableColumn from './DataTableColumn'

class DataTable extends Component {
    constructor(props) {
        super(props);
        this.state = {
            sortedBy: null,
            sortedAsc: true,
            groupedBy: null,
            subGroupedBy: null
        };
    }

    handleSortClick(column, asc){
        this.setState((prevState, props) => {
            return {
                sortedBy: column,
                sortedAsc: asc,
                groupedBy: prevState.groupedBy,
                subGroupedBy: prevState.subGroupedBy
            };
        });
    }

    render() {
        var that = this;
        return (
            <div className="statistics-table">
                <table>
                    <thead>
                        <tr>
                            {this.props.datatable.columns.map(function(col){
                                return <DataTableColumn
                                    key={col.key}
                                    datacolumn={col}
                                    handlesortclick={(i, sortasc) => that.handleSortClick(i, sortasc)} />
                            })}
                        </tr>
                    </thead>
                    {this.renderTableEntries()}
                </table>
            </div>
        );
    }

    renderTableEntries(){
        let processedEntries = this.props.datatable.dataentries;
        let columns = this.props.datatable.columns;

        if(this.state.sortedBy!=null){
            (this.state.sortedAsc) ? processedEntries.sort(this.state.sortedBy.sortfunc) : processedEntries.sort(this.state.sortedBy.sortfuncdesc)
        }

        // grouping is processed before entries get sorted inside the groups
        processedEntries = this.props.datatable.columns[1].groupBy(processedEntries);
        var subgroupf = this.props.datatable.columns[3].groupBy;
        Object.keys(processedEntries).map(function (group) {
            processedEntries[group] = subgroupf(processedEntries[group].entries);
        });


        // group if the entry contains keys
        if(Object.keys(processedEntries).length>0) {
            return (
                Object.keys(processedEntries).map(function (group) {
                    var groupobj = processedEntries[group];
                    return (
                        Object.keys(groupobj).map(function(subgroup){
                            return <DataTableGroup key={subgroup} groupname={group} subgroupname={groupobj[subgroup].name} groupentries={groupobj[subgroup].entries} columns={columns} />
                        })
                    )
                })
            );
        }
        else{
            return(
                processedEntries.map(function(nodes){
                    return <DataTableEntry key={nodes.username} entryvalues={nodes} columns={columns}/>;
                })
            );
        }
    }
}

export default DataTable;