import React, { Component } from 'react';
import DataTableEntry from './DataTableEntry'

class DataTable extends Component {
    constructor(props) {
        super(props);
    }

    render() {
        return (
            <div className="statistics-table">
                <table>
                    <thead>
                        <tr>
                            <th>Username <button id="stats-sort-username" onClick={() => this.props.sortClick("username")}>S</button></th>
                            <th>Requesttime <button id="stats-sort-username" onClick={() => this.props.sortClick("requesttime")}>S</button></th>
                            <th>Outlet <button id="stats-sort-username" onClick={() => this.props.sortClick("outlet")}>S</button></th>
                        </tr>
                    </thead>
                    <tbody>
                        {this.props.entries.map(function (nodes) {
                            return <DataTableEntry key={nodes.username} entryvalues={nodes} />
                        })}
                    </tbody>
                </table>
            </div>
        );
    }
}

export default DataTable;