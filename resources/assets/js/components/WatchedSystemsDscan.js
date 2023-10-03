import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import { Link } from 'react-router-dom';
import Moment from 'react-moment';

const API = 'https://recon.gnf.lt/api/coordination/watched_systems_dscan';

export default class WatchedSystemsDscan extends Component {

  constructor(props) {
    super(props);

    this.state = {
      watchedsystemsdscan: []
  }

  this.interval = null;
  }

  componentDidMount() {
    this.interval = setInterval(() => this.fetchWatchedSystemsDscan(), 5000);
    this.fetchWatchedSystemsDscan();
  }

  componentWillUnmount() {
    clearInterval(this.interval);
  }

  fetchWatchedSystemsDscan() {
    fetch(API)
      .then(response => response.json())
      .then((data) => this.setState({watchedsystemsdscan: data}))
    .catch((error) => {
      this.setState({
        watchedsystemsdscan: []
      }, () => console.log(error))
    });
  }

    renderTableData() {
      return this.state.watchedsystemsdscan.map((system, key) => {
    return (
      <tr key={key}>
        <td><a rel="noopener noreferrer" href={"https://evemaps.dotlan.net/map/" + system.region_name.replace(" ", "_") + "/" + system.solar_system_name + "#sov"} target="_blank">{system.solar_system_name}</a></td>
        <td>{system.constellation_name}</td>
        <td>{system.region_name}</td>
        <td>{system.dscan}</td>
        <td><a rel="noopener noreferrer" href={system.adash_url} target="_blank">{system.adash_url}</a></td>
        <td>{system.updated_at}</td>
      </tr>
    )
     })
   }

   render() {
    return (
    <div className="table-responsive" id="watched-systems-table-wrapper">
      <table className="table table-borderless table-striped">
      <thead>
        <tr>
          <th>Solar System</th>
          <th>Constellation</th>
          <th>Region</th>
          <th>Seen Hulls</th>
          <th>aD</th>
          <th>Last Updated</th>
        </tr>
      </thead>
      <tbody>
        {
          this.state.watchedsystemsdscan ? this.renderTableData() :
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

if (document.getElementById('watched_systems')) {
  ReactDOM.render(<WatchedSystemsDscan />, document.getElementById('watched_systems_dscan'));
}