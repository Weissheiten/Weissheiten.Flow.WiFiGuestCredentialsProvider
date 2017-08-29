import React, { Component } from 'react';
import DataTableEntry from './DataTableEntry'

class DataTableGroup extends Component {
    constructor(props) {
        super(props);
        this.state = {
            expanded: true
        }
    }

    render() {
        var that = this;

        return(
            <tbody className="statistics-table-group">
                <tr>
                    <td colSpan={that.props.columns.length-1}>{this.props.name}</td>
                    <td>{this.props.count}</td>
                </tr>
                {this.props.groupentries.map(function(nodes){
                    return <DataTableEntry key={nodes.username} entryvalues={nodes} columns={that.props.columns}/>
                })}
            </tbody>
        );
    }
}

export default DataTableGroup;