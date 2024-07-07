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

  private userFollowingsSubject = new BehaviorSubject<UserFollow[] | null>(null);
  private userFollowings$ = this.userFollowingsSubject.asObservable();

  private userFollowersSubject = new BehaviorSubject<UserFollow[] | null>(null);
  private userFollowers$  = this.userFollowersSubject.asObservable();

  constructor(private httpClient: HttpClient){}

  /**
   * solicita informacoes do user a api
   * @param urlProfile url para api do perfil user alvo
   * @returns observable
   */
  requestUserProfile(urlProfile : string){
    const params = new HttpParams().set('url', urlProfile);
    return this.httpClient.get<UserProfile>(`${this.apiUrl}/get`, { params }).pipe(
      tap(profile =>
        this.userProfileSubject.next(profile)
      ));
  }


  /**
   * solicita seguidos do user
   * @param urlProfile url para api do perfil user alvo
   * @param page qual pagina quer
   * @returns de 1 ate 50 users
  */
 requestUserFollowings(urlProfile: string, page: number){
   const params = new HttpParams()
   .set('url', urlProfile)
   .set('page', page);

   return this.httpClient.get<UserFollow[]>(this.apiUrl + '/getFollowings', {params}).pipe(
    tap(profile =>
      this.userFollowingsSubject.next(profile)
    ));
  }

  /**
   *
   * @param urlProfile url para api do perfil user alvo
   * @returns de 1 ate 50(maximo) users
  */
  requestUserFollowers(urlProfile: string){
    const params = new HttpParams().set('url', urlProfile);
    return this.httpClient.get<UserFollow[]>(this.apiUrl + '/getFollowers', {params}).pipe(
      tap(profile =>
        this.userFollowersSubject.next(profile)
      ));
  }

  /**
   * @returns observable dos dados do user solicitado (se houver)
   */
  getUserProfile(): Observable<UserProfile | null>{
    return this.userProfile$;
  }

  getUserFollowings() {
    return this.userFollowings$;
  }

  getUserFollowers() {
    return this.userFollowers$;
  }
}
