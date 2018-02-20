package AuthenticationServices;

import android.app.Service;
import android.content.Intent;
import android.os.IBinder;
import android.support.annotation.UiThread;

import retrofit2.Call;
import retrofit2.Retrofit;
import retrofit2.converter.gson.GsonConverterFactory;
import retrofit2.http.Body;
import retrofit2.http.Field;
import retrofit2.http.FormUrlEncoded;
import retrofit2.http.POST;

public interface WolfpackClient{

    //String BASE_URL = "http://wolfpack.cs.hbg.psu.edu/";
    String BASE_URL = "https://www.mocky.io/";
    //String FEED = "/Sign_up.php";
    String FEED = "v2/5a8b8eda320000cb271abed8";

    Retrofit retrofit = new Retrofit.Builder()
            .baseUrl(BASE_URL)
            .addConverterFactory(GsonConverterFactory.create())
            .build();

    @FormUrlEncoded
    @POST(FEED)
    Call<LoginDetails[]> attemptLogin(
            @Field("first_name") String first_name,
            @Field("last_name") String last_name,
            @Field("inputEmail") String email,
            @Field("inputPassword") String password
    );

}