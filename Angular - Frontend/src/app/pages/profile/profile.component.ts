import { Component } from '@angular/core';
import { NavComponent } from '../../components/nav/nav.component';
import { AccountBoxComponent } from '../../components/account-box/account-box.component';

@Component({
  selector: 'app-profile',
  standalone: true,
  imports: [NavComponent, AccountBoxComponent],
  templateUrl: './profile.component.html',
  styleUrl: './profile.component.css'
})
export class ProfileComponent {
  avatarPath: string = '../../../assets/images/avatar.jpg';
}
