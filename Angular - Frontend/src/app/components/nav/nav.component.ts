import { Component } from '@angular/core';
import { UserProfileService } from '../../services/user-profile.service';
import { UserProfile } from '../../models/user-profile.model';
import { FormsModule } from '@angular/forms';
import { LoadingComponent } from '../loading/loading.component';
import { CommonModule } from '@angular/common';
import { ToastrService } from 'ngx-toastr';
import { Router } from '@angular/router';
import { HttpErrorResponse } from '@angular/common/http';

@Component({
  selector: 'app-nav',
  standalone: true,
  imports: [FormsModule, LoadingComponent, CommonModule],
  templateUrl: './nav.component.html',
  styleUrl: './nav.component.css'
})
export class NavComponent {
  isLoading : boolean = false;
  urlProfile : string = '';
  isFormDisabled : boolean = false;

  constructor(private userService: UserProfileService, private toastr: ToastrService, private router: Router){}

  getUserProfile(){
    this.isFormDisabled = true;
    this.isLoading = true;

    this.userService.requestUserProfile(this.urlProfile).subscribe({
      next: () => {
        this.router.navigate(['/profile', this.urlProfile]);
      },
      error: (erro: HttpErrorResponse) => {
        this.isFormDisabled = false;
        this.isLoading = false;

        if (erro.status === 400) {
          this.showError('Erro na comunicação com servidor ou requisição');
        } else if (erro.status === 404) {
          this.showError('Usuário não encontrado');
        } else if (erro.status === 500) {
          this.showError('Houve algum erro no servidor');
        } else if (erro.status === 403) {
          this.showError('Limite de requisições excedido');
        } else {
          this.showError('Erro inesperado!');
        }
      }
    });
  }

  showError(msg: string){
    this.toastr.error(msg, '',{
      closeButton: true,
      timeOut: 3000,
      progressBar: true,
      positionClass: 'toast-top-center',
    });
  }

}
