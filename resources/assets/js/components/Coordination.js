import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import Moment from 'react-moment';

const API = 'https://recon.gnf.lt/api/coordination/active_fleets';

export default class Coordination extends Component {

  constructor(props) {
    super(props);

    this.state = {
      fleets: []
  }

  this.interval = null;
  }

  componentDidMount() {
    this.interval = setInterval(() => this.fetchFleets(), 5000);
    this.fetchFleets();
  }

  componentWillUnmount() {
    clearInterval(this.interval);
  }

  fetchFleets() {
    fetch(API)
      .then(response => response.json())
      .then((data) => this.setState({fleets: data}))
    .catch((error) => {
      this.setState({
        fleets: []
      }, () => console.log(error))
    });
  }

    renderTableData() {
      return this.state.fleets.map((fleet, key) => {
    return (
      <tr key={key}>
        <td>{fleet.fleet_owner}</td>
        <td>{fleet.fleet_boss}</td>
        <td>{fleet.fleet_size}</td>
        <td>{fleet.freemove}</td>
        <td>{fleet.advert}</td>
        <td>{fleet.system_numbers}</td>
        <td>{fleet.hull_numbers}</td>
        <td>{fleet.created_at}</td>
        <td>{fleet.updated_at}</td>
      </tr>
    )
     })
   }

   render() {
    return (
    <div className="table-responsive" id="contracts-table-wrapper">
      <table className="table table-borderless table-striped">
      <thead>
        <tr>
          <th>Fleet Owner</th>
          <th>Fleet Boss</th>
          <th>Size</th>
          <th>Free Move</th>
          <th>Advert</th>
          <th>Pilots In Systems</th>
          <th>Pilots In Ships</th>
          <th>Created At</th>
          <th>Last Updated</th>
        </tr>
      </thead>
      <tbody>
        {
          this.state.fleets ? this.renderTableData() :
          (
            <tr>
              <td colspan="7">There are now active fleets being tracked by this site!</td>
            </tr>
          )
        }
      </tbody>
    </table>
    </div>
    )
  }
}

if (document.getElementById('fleet')) {
  ReactDOM.render(<Coordination />, document.getElementById('fleet'));
}