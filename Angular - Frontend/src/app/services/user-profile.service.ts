import { HttpClient, HttpParams } from "@angular/common/http";
import { Injectable } from "@angular/core";
import { environment } from "../../environments/environment";
import { UserProfile } from "../models/user-profile.model";
import { tap } from "rxjs/operators";
import { BehaviorSubject } from "rxjs/internal/BehaviorSubject";
import { Observable } from "rxjs/internal/Observable";
import { UserFollow } from "../models/user-follow.model";


@Injectable({
  providedIn: 'root'
})
export class UserProfileService {

  private apiUrl = environment.api;

  private userProfileSubject = new BehaviorSubject<UserProfile | null>(null);
  private userProfile$ = this.userProfileSubject.asObservable();

  constructor(private httpClient: HttpClient){}

  /**
   * solicita informacoes do user a api
   * @param urlProfile url para api do perfil user alvo
   * @returns observable
   */
  requestUserProfile(urlProfile : string){
    const params = new HttpParams().set('url', urlProfile);
    return this.httpClient.get<UserProfile>(this.apiUrl + '/get', {params}).pipe(
      tap((profile: UserProfile) => {
        this.userProfileSubject.next(profile);
      })
    );
  }

  /**
   * @returns observable dos dados do user solicitado (se houver)
   */
  getUserProfile(): Observable<UserProfile | null>{
    return this.userProfile$;
  }

  /**
   * solicita seguidos do user
   * @param urlProfile url para api do perfil user alvo
   * @param page qual pagina quer
   * @returns de 1 ate 50 users
   */
  getUserFollowings(urlProfile: string, page: number){
    const params = new HttpParams()
      .set('url', urlProfile)
      .set('page', page);

    return this.httpClient.get<UserFollow[]>(this.apiUrl + '/getFollowings', {params});
  }

  /**
   *
   * @param urlProfile url para api do perfil user alvo
   * @returns de 1 ate 50(maximo) users
   */
  getUserFollowers(urlProfile: string){
    const params = new HttpParams().set('url', urlProfile);
    return this.httpClient.get<UserFollow[]>(this.apiUrl + '/getFollowers', {params});
  }
}
