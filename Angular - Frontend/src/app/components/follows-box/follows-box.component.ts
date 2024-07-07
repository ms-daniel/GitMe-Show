import { Component, Input, OnChanges, OnInit } from '@angular/core';
import { UserProfileService } from '../../services/user-profile.service';
import { Observable } from 'rxjs/internal/Observable';
import { UserFollow } from '../../models/user-follow.model';
import { AccountBoxComponent } from '../account-box/account-box.component';
import { FormsModule } from '@angular/forms';
import { CommonModule } from '@angular/common';
import { HttpErrorResponse } from '@angular/common/http';
import { ToastrService } from 'ngx-toastr';
import { catchError } from 'rxjs/internal/operators/catchError';
import { ActivatedRoute } from '@angular/router';

@Component({
  selector: 'app-follows-box',
  standalone: true,
  imports: [
    AccountBoxComponent, FormsModule,
    CommonModule
  ],
  templateUrl: './follows-box.component.html',
  styleUrl: './follows-box.component.css',
  host:{
    class: 'd-flex flex-column justify-content-center align-items-center'
  }
})

export class FollowsBoxComponent implements OnInit, OnChanges {
  @Input({ required: true }) urlProfile!: string;
  @Input({ required: true }) numberFollowers!: number;
  @Input({ required: true }) numberFollowings!: number;

  isLoading: boolean = true;
  hasFollowings: boolean = true;
  hasFollowers: boolean = true;
  inFollowings: boolean = true;

  followings$ =  new Observable<UserFollow[] | null>();
  followers$ = new Observable<UserFollow[] | null>();
  filteredItems: UserFollow[] = [];

  searchText: string = '';

  originalFollowingsItems: UserFollow[] = [];
  originalFollowersItems: UserFollow[] = [];

  wingCurrentPage: number = 1;

  wingTotalPages: number = Math.ceil(this.numberFollowings/50);

  followingsPages?: number[];

  constructor(
    private userService: UserProfileService, private toastr: ToastrService,
    private route: ActivatedRoute
  ){}

  ngOnInit(): void {
    this.route.queryParams.subscribe(params => {
      this.urlProfile = params['url'] ?? '';
      if (this.urlProfile) {
        this.wingCurrentPage = 1;
        if(this.numberFollowers > 0){
          this.callFollowers();
        }
        if(this.numberFollowings > 0){
          this.callFollowing();
        }

        console.log(this.followingsPages);
        this.filteredItems = [];
      }
    });
  }
  ngOnChanges(): void {
    this.hasFollowings = true;
    this.wingTotalPages = Math.ceil(this.numberFollowings/50);
    this.followingsPages = Array.from({ length: this.wingTotalPages }, (_, i) => i+1);

    console.log('alterou: ', this.followingsPages);
  }

  private callFollowers(): void {
    this.followers$ = this.userService.requestUserFollowers(this.urlProfile).pipe(
      catchError((erro: HttpErrorResponse) => {
        this.handleError(erro, 'Seguidores');
        return [];
      })
    );

    this.followers$.subscribe(items => {
      if (items != null && items.length > 0) {
        this.originalFollowersItems = items;
        this.hasFollowers = true;
      } else {
        this.hasFollowers = false;
      }
    });
  }

  switchToFollowers(): void {
    this.inFollowings = false;
    this.filteredItems = this.originalFollowersItems;
  }

  switchToFollowings(): void {
    this.inFollowings = true;
    this.filteredItems = this.originalFollowingsItems;
  }

  getMoreFollowing(move: string): void{
    if(move === '>' && this.wingCurrentPage < this.wingTotalPages){
      this.filteredItems = [];
      this.wingCurrentPage++;
      this.isLoading = true;
      this.callFollowing();
    } else if(move === '<' && this.wingCurrentPage > 1){
      this.filteredItems = [];
      this.wingCurrentPage--;
      this.isLoading = true;
      this.callFollowing();
    } else if (+move != this.wingCurrentPage && move != '<' && move != '>'){
      this.filteredItems = [];
      this.wingCurrentPage = +move;
      this.isLoading = true;
      this.callFollowing();
    }
  }

  /**
   * Carrega os seguidos do usuario
   */
  private callFollowing() {
    this.followings$ = this.getFollowings(this.wingCurrentPage);

    this.followings$.subscribe(items => {
      this.isLoading = false;
      if (items != null && items.length > 0) {
        this.originalFollowingsItems = items;
        this.filteredItems = this.originalFollowingsItems;
        this.hasFollowings = true;
      } else {
        this.hasFollowings = false;
      }
    });
  }

  private getFollowings(page: number): Observable<UserFollow[] | null> {
    return this.userService.requestUserFollowings(this.urlProfile, page).pipe(
      catchError ((erro: HttpErrorResponse) => {
        this.handleError(erro, 'Seguidos');
        return [];
      })
    );
  }

  /**
   * filtro de busca para seguidos e seguidores
   */
  search(): void {
    if(this.inFollowings && this.numberFollowings > 0){
      this.filteredItems = this.originalFollowingsItems.filter(item =>
        item.username.toLowerCase().startsWith(this.searchText.toLowerCase())
      );
    } else if (!this.inFollowings && this.numberFollowers > 0) {
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
