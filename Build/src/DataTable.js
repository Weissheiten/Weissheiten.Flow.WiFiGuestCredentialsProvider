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

    render() {
        return (
            <div className="statistics-table">
                <table>
                    <thead>
                        <tr>
                            {this.props.datatable.columns.map(function(col){
                                return <DataTableColumn key={col.key} coldescription={col.header} valuefield={col.lookupproperty} />
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

        processedEntries.sort(
            (a, b) => a.outlet.name.localeCompare(b.outlet.name)
        );

        return (
            processedEntries.map(function (nodes) {
                return <DataTableEntry key={nodes.username} entryvalues={nodes} columns={columns} />;
            })
        );
    }
}

export default DataTable;