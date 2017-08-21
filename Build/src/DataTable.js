import React, { Component } from 'react';
import DataTableEntry from './DataTableEntry'
import DataTableColumn from './DataTableColumn'

class DataTable extends Component {
    constructor(props) {
        super(props);
        this.state = {
            sortedBy: null,
            groupedBy: null
        };
    }

    handleSortClick(column){
        this.setState((prevState, props) => {
            return {sortedBy: column, groupedBy: prevState.groupedBy};
        });


        //alert(sortProperty);
        /*
        var vouchersSorted = this.state.vouchers.slice(0);

        vouchersSorted.sort(
            (a, b) => a.outlet.name.localeCompare(b.outlet.name)
        );

        this.setState({
            vouchers: vouchersSorted
        });
        */
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
                                    handlesortclick={(i) => that.handleSortClick(i)} />
                            })}
                        </tr>
                    </thead>
                    <tbody>
                        {this.renderTableEntries()}
                    </tbody>
                </table>
            </div>
        );
    }

    renderTableEntries(){
        let processedEntries = this.props.datatable.dataentries;
        let columns = this.props.datatable.columns;

        if(this.state.sortedBy!=null){
            processedEntries.sort(this.state.sortedBy.sortfunc);
        }

        return (
            processedEntries.map(function (nodes) {
                return <DataTableEntry key={nodes.username} entryvalues={nodes} columns={columns} />;
            })
        );
    }
}

export default DataTable;