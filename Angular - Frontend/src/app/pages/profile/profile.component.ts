import { Component, OnInit } from '@angular/core';
import { NavComponent } from '../../components/nav/nav.component';
import { AccountBoxComponent } from '../../components/account-box/account-box.component';
import { UserProfileService } from '../../services/user-profile.service';
import { UserProfile } from '../../models/user-profile.model';
import { Observable } from 'rxjs';
import { CommonModule } from '@angular/common';
import { ActivatedRoute } from '@angular/router';
import { FollowsBoxComponent } from '../../components/follows-box/follows-box.component';

@Component({
  selector: 'app-profile',
  standalone: true,
  imports: [
    NavComponent, AccountBoxComponent,
    CommonModule, FollowsBoxComponent
  ],
  templateUrl: './profile.component.html',
  styleUrl: './profile.component.css'
})
export class ProfileComponent implements OnInit{
  avatarPath: string = '../../../assets/images/avatar.jpg';

  urlProfile: string = '';

  userProfile$ = new Observable<UserProfile | null>();

  constructor(private userService: UserProfileService, private route: ActivatedRoute){}

  ngOnInit(): void {
    this.urlProfile = this.route.snapshot.paramMap.get('url') ?? '';

    this.userProfile$ = this.userService.getUserProfile();
  }


}
