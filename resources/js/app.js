import './bootstrap';

import {NodeImageProgram}  from "@sigma/node-image";
import Graph  from "graphology";
import Sigma  from "sigma";


/*
  Add custom scripts here
*/
import.meta.glob([
  '../assets/img/**',
  // '../assets/json/**',
  '../assets/vendor/fonts/**'
]);

const sigma= require('sigma');
window.Graph= Graph ;

window.Sigma= Sigma ;

window.NodeImageProgram = NodeImageProgram  ;