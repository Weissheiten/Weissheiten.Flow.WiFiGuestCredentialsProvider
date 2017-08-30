import React, { Component } from 'react';
import DataTableEntry from './DataTableEntry';

import "./scss/DataTableGroup.scss";

class DataTableGroup extends Component {
    constructor(props) {
        super(props);
        this.state = {
            expanded: false
        }
    }

    render() {
        var that = this;

        let groupClass = (this.state.expanded) ? 'statistics-table-group' : 'statistics-table-group-collapsed';

        return(
            <tbody className={groupClass}>
                <tr>
                    <td colSpan={that.props.columns.length} onClick={() => this.triggerCollapse()}>{this.props.name} ({this.props.groupentries.length})</td>
                </tr>
                {this.props.groupentries.map(function(nodes){
                    return <DataTableEntry key={nodes.username} entryvalues={nodes} columns={that.props.columns}/>
                })}
            </tbody>
        );
    }

    triggerCollapse(){
        this.setState((prevState, props) => {
            let isExpanded = prevState.expanded;
            return {
                expanded: !isExpanded,
            };
        });
    }
}

export default DataTableGroup;