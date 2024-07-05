import { HttpClient, HttpParams } from "@angular/common/http";
import { Injectable } from "@angular/core";
import { environment } from "../../environments/environment";
import { UserProfile } from "../models/user-profile.model";


@Injectable({
  providedIn: 'root'
})
export class UserProfileService {

  private apiUrl = environment.api;

  constructor(private httpClient: HttpClient){}

  getUserProfile(urlProfile : string){
    const params = new HttpParams().set('url', urlProfile);
    return this.httpClient.get<UserProfile>(this.apiUrl + '/get', {params});
  }

  getUserFollowings(){

  }
}
