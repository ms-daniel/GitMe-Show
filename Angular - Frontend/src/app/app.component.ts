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
export class AppComponent {
  constructor(private userService: UserProfileService){
    //console.log(environment.api);
    //console.log('Daniel é gostoso');
    this.getUserProfile();
  }

  urlProfile: string = 'https://api.github.com/users/ms-daniel';

  imageUrl: string = '';

  getUserProfile(){
    this.userService.getUserProfile(this.urlProfile).subscribe({
      next: (profile : UserProfile) => {
        console.log('user profile: ');
        console.log(profile);
        this.imageUrl = profile.avatar_url;
      },
      error: (erro) => {
        console.log('deu errado, moral' + erro);
      }
    });
  }
}
