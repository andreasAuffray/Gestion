import { startStimulusApp } from '@symfony/stimulus-bundle';
import ProjectBoardController from './controllers/ProjectBoardController.js';

export const app = startStimulusApp();

app.register('ProjectBoardController', ProjectBoardController);
