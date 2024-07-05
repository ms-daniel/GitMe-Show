import { Component } from '@angular/core';
import { RouterOutlet } from '@angular/router';
import { HomeComponent } from './pages/home/home.component';
import { NavComponent } from './components/nav/nav.component';
import { BaseUiComponent } from './components/base-ui/base-ui.component';
import { HttpClientModule } from '@angular/common/http';
import { environment } from '../environments/environment';
import { UserProfileService } from './services/user-profile.service';
import { UserProfile } from './models/user-profile.model';

@Component({
  selector: 'app-root',
  standalone: true,
  imports: [
    RouterOutlet, HomeComponent,
    NavComponent, BaseUiComponent,
    HttpClientModule
  ],
  templateUrl: './app.component.html',
  styleUrl: './app.component.css'
})
export class AppComponent {}
