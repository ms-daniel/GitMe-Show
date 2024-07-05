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
  isLoading: boolean = true;
  hasFollowings: boolean = true;

  //mandar da page profile
  @Input({ required: true }) urlProfile!: string;

  followings$ =  new Observable<UserFollow[]>();
  filteredItems: UserFollow[] = [];

  searchText: string = '';

  originalItems: UserFollow[] = [];

  constructor(private userService: UserProfileService, private toastr: ToastrService){}

  ngOnInit(): void {
    this.followings$ = this.userService.getUserFollowings(this.urlProfile).pipe(
      catchError((erro: HttpErrorResponse) => {
        this.handleError(erro);
        return [];
      })
    );

    this.followings$.subscribe(items => {
      this.isLoading = false;
      if(items != null && items.length > 0){
        this.originalItems = items;
        this.filteredItems = this.originalItems;
        this.hasFollowings = true;
      } else {
        console.log('entrou aqui hein');
        this.hasFollowings = false;
      }
    })


  }

  search(): void {
    this.filteredItems = this.originalItems.filter(item =>
      item.username.toLowerCase().startsWith(this.searchText.toLowerCase())
    );
  }

  showError(msg: string){
    this.toastr.error(msg, '',{
      closeButton: true,
      timeOut: 3000,
      progressBar: true,
      positionClass: 'toast-top-center',
    });
  }

  handleError(error: HttpErrorResponse): void {
    let errorMessage = 'Erro inesperado ao carregar followings!';
    if (error.status === 400) {
      errorMessage = 'Erro na comunicação com servidor ou requisição (followings)';
    } else if (error.status === 404) {
      errorMessage = 'Usuário(s) não encontrado (followings)';
    } else if (error.status === 500) {
      errorMessage = 'Houve algum erro no servidor (followings)';
    } else if (error.status === 403) {
      errorMessage = 'Limite de requisições excedido (followings)';
    }
    this.showError(errorMessage);
  }
}
