package AuthenticationServices;


import java.util.List;

import retrofit2.Call;
import retrofit2.Retrofit;
import retrofit2.converter.gson.GsonConverterFactory;
import retrofit2.http.Field;
import retrofit2.http.FormUrlEncoded;
import retrofit2.http.GET;
import retrofit2.http.POST;

public interface WolfpackClient{

    //String BASE_URL = "http://wolfpack.cs.hbg.psu.edu/";
    String BASE_URL = "http://560057.youcanlearnit.net/";
    //String FEED = "/Sign_up.php";
    String FEED = "services/json/itemsfeed.php";

    Retrofit retrofit = new Retrofit.Builder()
            .baseUrl(BASE_URL)
            .addConverterFactory(GsonConverterFactory.create())
            .build();

    @GET(FEED)
    Call<List<LoginDetails>> dataItems();

    /*@FormUrlEncoded
    @POST(FEED)
    Call<LoginDetails[]> attemptLogin(
            @Field("first_name") String first_name,
            @Field("last_name") String last_name,
            @Field("inputEmail") String email,
            @Field("inputPassword") String password
    );*/

}