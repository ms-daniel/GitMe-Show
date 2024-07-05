import { Component, Input } from '@angular/core';

@Component({
  selector: 'app-account-box',
  standalone: true,
  imports: [],
  templateUrl: './account-box.component.html',
  styleUrl: './account-box.component.css',
  host: {
    'class': 'd-flex justify-content-center'
  }
})
export class AccountBoxComponent {
  imgPath: string = '../../../assets/images/female-avatar.svg';
  @Input({ required: true }) avatar_url!: string;
  @Input({ required: true }) github_link!: string;
  @Input({ required: true }) username!: string;
}
