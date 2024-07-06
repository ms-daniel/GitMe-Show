import { Component, Input, OnInit } from '@angular/core';
import { UserProfileService } from '../../services/user-profile.service';
import { Observable } from 'rxjs/internal/Observable';
import { UserFollow } from '../../models/user-follow.model';
import { AccountBoxComponent } from '../account-box/account-box.component';
import { FormsModule } from '@angular/forms';
import { CommonModule } from '@angular/common';
import { HttpErrorResponse } from '@angular/common/http';
import { ToastrService } from 'ngx-toastr';
import { catchError } from 'rxjs/internal/operators/catchError';

@Component({
  selector: 'app-follows-box',
  standalone: true,
  imports: [
    AccountBoxComponent, FormsModule,
    CommonModule
  ],
  templateUrl: './follows-box.component.html',
  styleUrl: './follows-box.component.css'
})

export class FollowsBoxComponent implements OnInit {
  @Input({ required: true }) urlProfile!: string;

  isLoading: boolean = true;
  hasFollowings: boolean = true;
  hasFollowers: boolean = true;
  inFollowings: boolean = true;

  followings$ =  new Observable<UserFollow[]>();
  followers$ = new Observable<UserFollow[]>();
  filteredItems: UserFollow[] = [];

  searchText: string = '';

  originalFollowingsItems: UserFollow[] = [];
  originalFollowersItems: UserFollow[] = [];

  constructor(private userService: UserProfileService, private toastr: ToastrService){}

  ngOnInit(): void {
    this.followings$ = this.userService.getUserFollowings(this.urlProfile).pipe(
      catchError((erro: HttpErrorResponse) => {
        this.handleError(erro, 'Seguidos');
        return [];
      })
    );

    this.followers$ = this.userService.getUserFollowers(this.urlProfile).pipe(
      catchError((erro: HttpErrorResponse) => {
        this.handleError(erro, 'Seguidores');
        return [];
      })
    );

    this.followings$.subscribe(items => {
      this.isLoading = false;
      if(items != null && items.length > 0){
        this.originalFollowingsItems = items;
        this.filteredItems = this.originalFollowingsItems;
        this.hasFollowings = true;
      } else {
        this.hasFollowings = false;
      }
    })

    this.followers$.subscribe(items => {
      if(items != null && items.length > 0){
        this.originalFollowersItems = items;
        this.hasFollowers = true;
      } else {
        this.hasFollowers = false;
      }
    })
  }

  search(): void {
    if(this.inFollowings){
      this.filteredItems = this.originalFollowingsItems.filter(item =>
        item.username.toLowerCase().startsWith(this.searchText.toLowerCase())
      );
    } else {
      this.filteredItems = this.originalFollowersItems.filter(item =>
        item.username.toLowerCase().startsWith(this.searchText.toLowerCase())
      );
    }
  }

  showError(msg: string){
    this.toastr.error(msg, '',{
      closeButton: true,
      timeOut: 3000,
      progressBar: true,
      positionClass: 'toast-top-center',
    });
  }

  handleError(error: HttpErrorResponse, follow: string): void {
    let errorMessage = `Erro inesperado ao carregar ${follow}!`;
    if (error.status === 400) {
      errorMessage = `Erro na comunicação com servidor ou requisição (${follow})`;
    } else if (error.status === 404) {
      errorMessage = `Usuário(s) não encontrado (${follow})`;
    } else if (error.status === 500) {
      errorMessage = `Houve algum erro no servidor (${follow})`;
    } else if (error.status === 403) {
      errorMessage = `Limite de requisições excedido (${follow})`;
    }
    this.showError(errorMessage);
  }
}
