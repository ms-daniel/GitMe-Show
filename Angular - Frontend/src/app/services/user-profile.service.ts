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
  private urlProfile?: string;

  constructor(private httpClient: HttpClient){}

  requestUserProfile(urlProfile : string){
    this.urlProfile = urlProfile;

    const params = new HttpParams().set('url', urlProfile);
    return this.httpClient.get<UserProfile>(this.apiUrl + '/get', {params}).pipe(
      tap((profile: UserProfile) => {
        this.userProfileSubject.next(profile);
      })
    );
  }

  getUserProfile(): Observable<UserProfile | null>{
    return this.userProfile$;
  }

  getUrlProfile(): string | undefined {
    return this.urlProfile;
  }

  getUserFollowings(urlProfile: string, page: number){
    const params = new HttpParams()
      .set('url', urlProfile)
      .set('page', page);

    return this.httpClient.get<UserFollow[]>(this.apiUrl + '/getFollowings', {params});
  }

  getUserFollowers(urlProfile: string){
    const params = new HttpParams().set('url', urlProfile);
    return this.httpClient.get<UserFollow[]>(this.apiUrl + '/getFollowers', {params});
  }
}
