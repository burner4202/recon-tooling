import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import { Link } from 'react-router-dom';
import Moment from 'react-moment';

const API = 'https://recon.gnf.lt/api/coordination/watched_systems';

export default class WatchedSystems extends Component {

  constructor(props) {
    super(props);

    this.state = {
      watchedsystems: []
  }

  this.interval = null;
  }

  componentDidMount() {
    this.interval = setInterval(() => this.fetchWatchedSystems(), 5000);
    this.fetchWatchedSystems();
  }

  componentWillUnmount() {
    clearInterval(this.interval);
  }

  fetchWatchedSystems() {
    fetch(API)
      .then(response => response.json())
      .then((data) => this.setState({watchedsystems: data}))
    .catch((error) => {
      this.setState({
        watchedsystems: []
      }, () => console.log(error))
    });
  }

    renderTableData() {
      return this.state.watchedsystems.map((system, key) => {
    return (
      <tr key={key}>
        <td><a rel="noopener noreferrer" href={"https://evemaps.dotlan.net/map/" + system.region_name.replace(" ", "_") + "/" + system.solar_system_name + "#sov"} target="_blank">{system.solar_system_name}</a></td>
        <td>{system.constellation_name}</td>
        <td>{system.region_name}</td>
        <td>{system.local_numbers}</td>
        <td>{system.local_alliances}</td>
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
          <th>Local Numbers</th>
          <th>Alliances</th>
          <th>aD</th>
          <th>Last Updated</th>
        </tr>
      </thead>
      <tbody>
        {
          this.state.watchedsystems ? this.renderTableData() :
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
  ReactDOM.render(<WatchedSystems />, document.getElementById('watched_systems'));
}